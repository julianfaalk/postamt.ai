<?php
/**
 * Platform API Limits Service
 * Tracks usage against free tier limits
 */

class PlatformLimits
{
    // Monthly post limits per platform (free tier)
    private const LIMITS = [
        'twitter' => 1500,  // X Free Tier: 1,500 posts/month app-wide
        'instagram' => 0,   // 0 = unlimited
        'tiktok' => 0,
        'linkedin' => 0,
        'youtube' => 0,
    ];

    /**
     * Check if we can post to a platform
     */
    public static function canPost(string $platform): bool
    {
        $limit = self::LIMITS[$platform] ?? 0;

        // 0 means unlimited
        if ($limit === 0) {
            return true;
        }

        $usage = self::getUsage($platform);
        return $usage['post_count'] < $usage['limit_count'];
    }

    /**
     * Get current usage for a platform
     */
    public static function getUsage(string $platform): array
    {
        $month = date('Y-m');
        $limit = self::LIMITS[$platform] ?? 0;

        $usage = Database::fetch(
            'SELECT * FROM platform_usage WHERE platform = ? AND month = ?',
            [$platform, $month]
        );

        if (!$usage) {
            // Create entry for this month
            Database::insert('platform_usage', [
                'platform' => $platform,
                'month' => $month,
                'post_count' => 0,
                'limit_count' => $limit,
            ]);

            return [
                'platform' => $platform,
                'month' => $month,
                'post_count' => 0,
                'limit_count' => $limit,
                'remaining' => $limit,
                'percentage' => 0,
            ];
        }

        $remaining = max(0, $usage['limit_count'] - $usage['post_count']);
        $percentage = $limit > 0 ? round(($usage['post_count'] / $limit) * 100) : 0;

        return array_merge($usage, [
            'remaining' => $remaining,
            'percentage' => $percentage,
        ]);
    }

    /**
     * Increment post count for a platform
     */
    public static function incrementUsage(string $platform): void
    {
        $month = date('Y-m');

        // Ensure entry exists
        self::getUsage($platform);

        // Increment counter
        Database::query(
            'UPDATE platform_usage SET post_count = post_count + 1 WHERE platform = ? AND month = ?',
            [$platform, $month]
        );
    }

    /**
     * Get remaining posts for a platform
     */
    public static function getRemaining(string $platform): int
    {
        $usage = self::getUsage($platform);
        return $usage['remaining'];
    }

    /**
     * Check if limit is close (< 10% remaining)
     */
    public static function isLimitWarning(string $platform): bool
    {
        $usage = self::getUsage($platform);

        if ($usage['limit_count'] === 0) {
            return false;
        }

        return $usage['percentage'] >= 90;
    }

    /**
     * Get all platform usage stats
     */
    public static function getAllUsage(): array
    {
        $stats = [];

        foreach (self::LIMITS as $platform => $limit) {
            if ($limit > 0) {
                $stats[$platform] = self::getUsage($platform);
            }
        }

        return $stats;
    }
}
