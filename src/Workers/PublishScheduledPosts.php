#!/usr/bin/env php
<?php
/**
 * Scheduled Posts Publisher Worker
 *
 * This script should be run via cron every minute:
 * * * * * * php /path/to/src/Workers/PublishScheduledPosts.php
 */

// Prevent web access
if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line.');
}

// Load application bootstrap (without routing)
require_once __DIR__ . '/../bootstrap.php';

// Prevent duplicate runs with lock file
$lockFile = __DIR__ . '/../../data/publish.lock';

if (file_exists($lockFile)) {
    $lockTime = filemtime($lockFile);
    // If lock is older than 5 minutes, remove it (stale lock)
    if (time() - $lockTime > 300) {
        unlink($lockFile);
    } else {
        echo "Worker already running, exiting.\n";
        exit(0);
    }
}

// Create lock
file_put_contents($lockFile, getmypid());

// Cleanup on exit
register_shutdown_function(function() use ($lockFile) {
    if (file_exists($lockFile)) {
        unlink($lockFile);
    }
});

echo "[" . date('Y-m-d H:i:s') . "] Starting scheduled posts worker...\n";

try {
    // Get posts ready to publish
    $posts = Database::fetchAll(
        "SELECT p.*, u.email as user_email
         FROM posts p
         JOIN users u ON p.user_id = u.id
         WHERE p.status IN ('scheduled', 'queued')
         AND p.scheduled_at <= datetime('now')
         ORDER BY p.scheduled_at ASC
         LIMIT 10"
    );

    if (empty($posts)) {
        echo "No posts to publish.\n";
        exit(0);
    }

    echo "Found " . count($posts) . " posts to publish.\n";

    foreach ($posts as $post) {
        echo "\nProcessing post #{$post['id']}...\n";

        // Mark as publishing
        Database::update('posts', [
            'status' => 'publishing',
            'updated_at' => date('Y-m-d H:i:s')
        ], 'id = ?', [$post['id']]);

        // Get platform assignments
        $platforms = Database::fetchAll(
            "SELECT pp.*, a.platform, a.access_token, a.refresh_token, a.token_expires_at, a.platform_username, a.platform_user_id
             FROM post_platforms pp
             JOIN accounts a ON pp.account_id = a.id
             WHERE pp.post_id = ? AND pp.status = 'pending'",
            [$post['id']]
        );

        if (empty($platforms)) {
            echo "  No platforms assigned, marking as published.\n";
            Database::update('posts', [
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ], 'id = ?', [$post['id']]);
            continue;
        }

        $allSuccess = true;
        $anySuccess = false;

        foreach ($platforms as $platform) {
            echo "  Publishing to {$platform['platform']} (@{$platform['platform_username']})...\n";

            try {
                $result = publishToplatform($platform, $post);

                // Update platform status
                Database::query(
                    "UPDATE post_platforms SET status = 'published', platform_post_id = ?, published_at = datetime('now') WHERE id = ?",
                    [$result['id'] ?? null, $platform['id']]
                );

                echo "    Success! Post ID: " . ($result['id'] ?? 'unknown') . "\n";
                $anySuccess = true;

            } catch (Exception $e) {
                echo "    Failed: " . $e->getMessage() . "\n";
                error_log("Post #{$post['id']} to {$platform['platform']} failed: " . $e->getMessage());

                // Update platform status
                Database::query(
                    "UPDATE post_platforms SET status = 'failed', error_message = ? WHERE id = ?",
                    [$e->getMessage(), $platform['id']]
                );

                $allSuccess = false;
            }
        }

        // Update post status
        $finalStatus = $allSuccess ? 'published' : ($anySuccess ? 'partial' : 'failed');
        Database::update('posts', [
            'status' => $finalStatus,
            'published_at' => $anySuccess ? date('Y-m-d H:i:s') : null,
            'updated_at' => date('Y-m-d H:i:s')
        ], 'id = ?', [$post['id']]);

        echo "  Post status: {$finalStatus}\n";
    }

    echo "\n[" . date('Y-m-d H:i:s') . "] Worker completed.\n";

} catch (Exception $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    error_log("PublishScheduledPosts worker error: " . $e->getMessage());
    exit(1);
}

/**
 * Publish content to a specific platform
 */
function publishToPlatform(array $platform, array $post): array
{
    // Check if token is expired and refresh if needed
    $accessToken = $platform['access_token'];

    if (!empty($platform['token_expires_at'])) {
        $expiresAt = strtotime($platform['token_expires_at']);
        if ($expiresAt && $expiresAt < time()) {
            $accessToken = refreshPlatformToken($platform);
        }
    }

    switch ($platform['platform']) {
        case 'twitter':
            return publishToTwitter($accessToken, $post);

        case 'instagram':
            throw new Exception('Instagram publishing not yet implemented');

        case 'linkedin':
            return publishToLinkedIn($accessToken, $platform['platform_user_id'], $post);

        case 'tiktok':
            throw new Exception('TikTok publishing not yet implemented');

        case 'youtube':
            throw new Exception('YouTube publishing not yet implemented');

        default:
            throw new Exception("Unknown platform: {$platform['platform']}");
    }
}

/**
 * Refresh expired access token
 */
function refreshPlatformToken(array $platform): string
{
    if (empty($platform['refresh_token'])) {
        throw new Exception('No refresh token available, re-authentication required');
    }

    switch ($platform['platform']) {
        case 'twitter':
            $twitter = new TwitterOAuth();
            $tokens = $twitter->refreshToken($platform['refresh_token']);

            // Update stored tokens
            Database::update('accounts', [
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'] ?? $platform['refresh_token'],
                'token_expires_at' => isset($tokens['expires_in'])
                    ? date('Y-m-d H:i:s', time() + $tokens['expires_in'])
                    : null,
                'updated_at' => date('Y-m-d H:i:s')
            ], 'id = ?', [$platform['account_id']]);

            return $tokens['access_token'];

        default:
            throw new Exception("Token refresh not implemented for {$platform['platform']}");
    }
}

/**
 * Publish to Twitter/X
 */
function publishToTwitter(string $accessToken, array $post): array
{
    $twitter = new TwitterOAuth();
    return $twitter->postTweet($accessToken, $post['content']);
}

/**
 * Publish to LinkedIn
 */
function publishToLinkedIn(string $accessToken, string $personId, array $post): array
{
    $linkedin = new LinkedInOAuth();
    $personUrn = 'urn:li:person:' . $personId;
    return $linkedin->createPost($accessToken, $personUrn, $post['content']);
}
