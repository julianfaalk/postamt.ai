<?php
/**
 * YouTube OAuth 2.0 Service
 * Handles authentication and posting to YouTube
 * Uses Google OAuth with YouTube Data API v3
 */

class YouTubeOAuth
{
    private const AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const CHANNEL_URL = 'https://www.googleapis.com/youtube/v3/channels';

    private HttpClient $http;

    public function __construct()
    {
        $this->http = new HttpClient();
    }

    /**
     * Generate YouTube OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        $state = $this->generateState();

        $params = [
            'client_id' => YOUTUBE_CLIENT_ID,
            'redirect_uri' => BASE_URL . '/auth/youtube/callback',
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/youtube.upload https://www.googleapis.com/auth/youtube https://www.googleapis.com/auth/userinfo.profile',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
        ];

        return self::AUTH_URL . '?' . http_build_query($params);
    }

    /**
     * Handle OAuth callback
     */
    public function handleCallback(string $code, int $userId): array
    {
        // Exchange code for tokens
        $tokens = $this->exchangeCodeForTokens($code);

        if (empty($tokens['access_token'])) {
            throw new Exception('Token-Austausch fehlgeschlagen');
        }

        // Get channel info
        $channelInfo = $this->getChannelInfo($tokens['access_token']);

        // Check if account already connected
        $existing = Database::fetch(
            'SELECT id FROM accounts WHERE user_id = ? AND platform = ? AND platform_user_id = ?',
            [$userId, 'youtube', $channelInfo['id']]
        );

        if ($existing) {
            // Update existing account
            Database::update('accounts', [
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'] ?? null,
                'token_expires_at' => isset($tokens['expires_in'])
                    ? date('Y-m-d H:i:s', time() + $tokens['expires_in'])
                    : null,
                'updated_at' => date('Y-m-d H:i:s'),
            ], 'id = ?', [$existing['id']]);

            return Database::fetch('SELECT * FROM accounts WHERE id = ?', [$existing['id']]);
        }

        // Create new account
        $accountId = Database::insert('accounts', [
            'user_id' => $userId,
            'platform' => 'youtube',
            'platform_user_id' => $channelInfo['id'],
            'platform_username' => $channelInfo['customUrl'] ?? $channelInfo['title'],
            'display_name' => $channelInfo['title'],
            'avatar_url' => $channelInfo['thumbnail'] ?? null,
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'] ?? null,
            'token_expires_at' => isset($tokens['expires_in'])
                ? date('Y-m-d H:i:s', time() + $tokens['expires_in'])
                : null,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return Database::fetch('SELECT * FROM accounts WHERE id = ?', [$accountId]);
    }

    /**
     * Refresh access token
     */
    public function refreshToken(string $refreshToken): array
    {
        $response = $this->http->post(self::TOKEN_URL, http_build_query([
            'client_id' => YOUTUBE_CLIENT_ID,
            'client_secret' => YOUTUBE_CLIENT_SECRET,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);

        if ($response['status'] !== 200) {
            throw new Exception('Token-Refresh fehlgeschlagen');
        }

        return $response['body'];
    }

    private function exchangeCodeForTokens(string $code): array
    {
        $response = $this->http->post(self::TOKEN_URL, http_build_query([
            'code' => $code,
            'client_id' => YOUTUBE_CLIENT_ID,
            'client_secret' => YOUTUBE_CLIENT_SECRET,
            'redirect_uri' => BASE_URL . '/auth/youtube/callback',
            'grant_type' => 'authorization_code',
        ]), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);

        if ($response['status'] !== 200) {
            $error = $response['body']['error_description'] ?? $response['body']['error'] ?? 'Token exchange failed';
            error_log('YouTube token exchange failed: ' . print_r($response, true));
            throw new Exception($error);
        }

        return $response['body'];
    }

    private function getChannelInfo(string $accessToken): array
    {
        $url = self::CHANNEL_URL . '?' . http_build_query([
            'part' => 'snippet',
            'mine' => 'true',
        ]);

        $response = $this->http->get($url, [
            'Authorization' => 'Bearer ' . $accessToken,
        ]);

        if ($response['status'] !== 200) {
            throw new Exception('YouTube Kanal-Info konnte nicht abgerufen werden');
        }

        if (empty($response['body']['items'])) {
            throw new Exception('Kein YouTube-Kanal gefunden. Bitte erstelle zuerst einen Kanal.');
        }

        $channel = $response['body']['items'][0];
        return [
            'id' => $channel['id'],
            'title' => $channel['snippet']['title'],
            'customUrl' => $channel['snippet']['customUrl'] ?? null,
            'thumbnail' => $channel['snippet']['thumbnails']['default']['url'] ?? null,
        ];
    }

    private function generateState(): string
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['youtube_oauth_state'] = $state;
        return $state;
    }

    public function verifyState(string $state): bool
    {
        if (empty($_SESSION['youtube_oauth_state'])) {
            return false;
        }
        $valid = hash_equals($_SESSION['youtube_oauth_state'], $state);
        unset($_SESSION['youtube_oauth_state']);
        return $valid;
    }
}
