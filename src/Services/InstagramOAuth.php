<?php
/**
 * Instagram OAuth Service
 * Uses Meta (Facebook) Graph API for Instagram Business/Creator accounts
 *
 * Requirements:
 * - Instagram account must be a Business or Creator account
 * - Must be connected to a Facebook Page
 */

class InstagramOAuth
{
    private const AUTH_URL = 'https://www.facebook.com/v18.0/dialog/oauth';
    private const TOKEN_URL = 'https://graph.facebook.com/v18.0/oauth/access_token';
    private const GRAPH_URL = 'https://graph.facebook.com/v18.0';

    private HttpClient $http;

    public function __construct()
    {
        $this->http = new HttpClient();
    }

    /**
     * Generate Instagram/Meta OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        $state = $this->generateState();

        $params = [
            'client_id' => INSTAGRAM_CLIENT_ID,
            'redirect_uri' => BASE_URL . '/auth/instagram/callback',
            'response_type' => 'code',
            'scope' => 'instagram_basic,instagram_content_publish,instagram_manage_comments,instagram_manage_insights,pages_show_list,pages_read_engagement',
            'state' => $state,
        ];

        return self::AUTH_URL . '?' . http_build_query($params);
    }

    /**
     * Handle OAuth callback
     */
    public function handleCallback(string $code, int $userId): array
    {
        // Exchange code for short-lived token
        $tokens = $this->exchangeCodeForTokens($code);

        if (empty($tokens['access_token'])) {
            throw new Exception('Token-Austausch fehlgeschlagen');
        }

        // Exchange for long-lived token
        $longLivedToken = $this->getLongLivedToken($tokens['access_token']);

        // Get Instagram Business Account
        $igAccount = $this->getInstagramAccount($longLivedToken['access_token']);

        // Check if account already connected
        $existing = Database::fetch(
            'SELECT id FROM accounts WHERE user_id = ? AND platform = ? AND platform_user_id = ?',
            [$userId, 'instagram', $igAccount['id']]
        );

        if ($existing) {
            // Update existing account
            Database::update('accounts', [
                'access_token' => $longLivedToken['access_token'],
                'token_expires_at' => isset($longLivedToken['expires_in'])
                    ? date('Y-m-d H:i:s', time() + $longLivedToken['expires_in'])
                    : null,
                'updated_at' => date('Y-m-d H:i:s'),
            ], 'id = ?', [$existing['id']]);

            return Database::fetch('SELECT * FROM accounts WHERE id = ?', [$existing['id']]);
        }

        // Create new account
        $accountId = Database::insert('accounts', [
            'user_id' => $userId,
            'platform' => 'instagram',
            'platform_user_id' => $igAccount['id'],
            'platform_username' => $igAccount['username'],
            'display_name' => $igAccount['name'] ?? $igAccount['username'],
            'avatar_url' => $igAccount['profile_picture_url'] ?? null,
            'access_token' => $longLivedToken['access_token'],
            'token_expires_at' => isset($longLivedToken['expires_in'])
                ? date('Y-m-d H:i:s', time() + $longLivedToken['expires_in'])
                : null,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return Database::fetch('SELECT * FROM accounts WHERE id = ?', [$accountId]);
    }

    /**
     * Refresh access token (extend long-lived token)
     */
    public function refreshToken(string $accessToken): array
    {
        $url = self::GRAPH_URL . '/oauth/access_token?' . http_build_query([
            'grant_type' => 'fb_exchange_token',
            'client_id' => INSTAGRAM_CLIENT_ID,
            'client_secret' => INSTAGRAM_CLIENT_SECRET,
            'fb_exchange_token' => $accessToken,
        ]);

        $response = $this->http->get($url);

        if ($response['status'] !== 200) {
            throw new Exception('Token-Refresh fehlgeschlagen');
        }

        return $response['body'];
    }

    /**
     * Create an Instagram post (image)
     */
    public function createPost(string $accessToken, string $igUserId, string $imageUrl, string $caption): array
    {
        // Step 1: Create media container
        $containerResponse = $this->http->post(
            self::GRAPH_URL . "/{$igUserId}/media",
            http_build_query([
                'image_url' => $imageUrl,
                'caption' => $caption,
                'access_token' => $accessToken,
            ]),
            ['Content-Type' => 'application/x-www-form-urlencoded']
        );

        if ($containerResponse['status'] !== 200) {
            throw new Exception('Instagram Container konnte nicht erstellt werden');
        }

        $containerId = $containerResponse['body']['id'];

        // Step 2: Publish the container
        $publishResponse = $this->http->post(
            self::GRAPH_URL . "/{$igUserId}/media_publish",
            http_build_query([
                'creation_id' => $containerId,
                'access_token' => $accessToken,
            ]),
            ['Content-Type' => 'application/x-www-form-urlencoded']
        );

        if ($publishResponse['status'] !== 200) {
            throw new Exception('Instagram Post konnte nicht veroeffentlicht werden');
        }

        return ['id' => $publishResponse['body']['id']];
    }

    private function exchangeCodeForTokens(string $code): array
    {
        $url = self::TOKEN_URL . '?' . http_build_query([
            'client_id' => INSTAGRAM_CLIENT_ID,
            'client_secret' => INSTAGRAM_CLIENT_SECRET,
            'redirect_uri' => BASE_URL . '/auth/instagram/callback',
            'code' => $code,
        ]);

        $response = $this->http->get($url);

        if ($response['status'] !== 200) {
            $error = $response['body']['error']['message'] ?? 'Token exchange failed';
            error_log('Instagram token exchange failed: ' . print_r($response, true));
            throw new Exception($error);
        }

        return $response['body'];
    }

    private function getLongLivedToken(string $shortLivedToken): array
    {
        $url = self::GRAPH_URL . '/oauth/access_token?' . http_build_query([
            'grant_type' => 'fb_exchange_token',
            'client_id' => INSTAGRAM_CLIENT_ID,
            'client_secret' => INSTAGRAM_CLIENT_SECRET,
            'fb_exchange_token' => $shortLivedToken,
        ]);

        $response = $this->http->get($url);

        if ($response['status'] !== 200) {
            throw new Exception('Long-lived Token konnte nicht erstellt werden');
        }

        return $response['body'];
    }

    private function getInstagramAccount(string $accessToken): array
    {
        // Get Facebook Pages the user manages
        $pagesUrl = self::GRAPH_URL . '/me/accounts?' . http_build_query([
            'access_token' => $accessToken,
        ]);

        $pagesResponse = $this->http->get($pagesUrl);

        if ($pagesResponse['status'] !== 200 || empty($pagesResponse['body']['data'])) {
            throw new Exception('Keine Facebook-Seite gefunden. Instagram Business-Account muss mit einer Facebook-Seite verbunden sein.');
        }

        // Get Instagram Business Account linked to the first page
        $page = $pagesResponse['body']['data'][0];
        $pageAccessToken = $page['access_token'];

        $igUrl = self::GRAPH_URL . "/{$page['id']}?" . http_build_query([
            'fields' => 'instagram_business_account{id,username,name,profile_picture_url}',
            'access_token' => $pageAccessToken,
        ]);

        $igResponse = $this->http->get($igUrl);

        if ($igResponse['status'] !== 200 || empty($igResponse['body']['instagram_business_account'])) {
            throw new Exception('Kein Instagram Business-Account gefunden. Bitte verbinde deinen Instagram-Account als Business/Creator mit deiner Facebook-Seite.');
        }

        return $igResponse['body']['instagram_business_account'];
    }

    private function generateState(): string
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['instagram_oauth_state'] = $state;
        return $state;
    }

    public function verifyState(string $state): bool
    {
        if (empty($_SESSION['instagram_oauth_state'])) {
            return false;
        }
        $valid = hash_equals($_SESSION['instagram_oauth_state'], $state);
        unset($_SESSION['instagram_oauth_state']);
        return $valid;
    }
}
