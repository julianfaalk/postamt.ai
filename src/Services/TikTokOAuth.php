<?php
/**
 * TikTok OAuth 2.0 Service
 * Handles authentication and posting to TikTok
 *
 * Requirements:
 * - TikTok for Developers account
 * - Content Posting API access (must be applied for)
 */

class TikTokOAuth
{
    private const AUTH_URL = 'https://www.tiktok.com/v2/auth/authorize/';
    private const TOKEN_URL = 'https://open.tiktokapis.com/v2/oauth/token/';
    private const USERINFO_URL = 'https://open.tiktokapis.com/v2/user/info/';

    private HttpClient $http;

    public function __construct()
    {
        $this->http = new HttpClient();
    }

    /**
     * Generate TikTok OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        $state = $this->generateState();
        $codeVerifier = $this->generateCodeVerifier();
        $codeChallenge = $this->generateCodeChallenge($codeVerifier);

        // Store code verifier for token exchange
        $_SESSION['tiktok_code_verifier'] = $codeVerifier;

        $params = [
            'client_key' => TIKTOK_CLIENT_KEY,
            'redirect_uri' => BASE_URL . '/auth/tiktok/callback',
            'response_type' => 'code',
            'scope' => 'user.info.basic,video.publish,video.upload',
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ];

        return self::AUTH_URL . '?' . http_build_query($params);
    }

    /**
     * Handle OAuth callback
     */
    public function handleCallback(string $code, int $userId): array
    {
        $codeVerifier = $_SESSION['tiktok_code_verifier'] ?? '';
        unset($_SESSION['tiktok_code_verifier']);

        // Exchange code for tokens
        $tokens = $this->exchangeCodeForTokens($code, $codeVerifier);

        if (empty($tokens['access_token'])) {
            throw new Exception('Token-Austausch fehlgeschlagen');
        }

        // Get user info
        $userInfo = $this->getUserInfo($tokens['access_token']);

        // Check if account already connected
        $existing = Database::fetch(
            'SELECT id FROM accounts WHERE user_id = ? AND platform = ? AND platform_user_id = ?',
            [$userId, 'tiktok', $userInfo['open_id']]
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
            'platform' => 'tiktok',
            'platform_user_id' => $userInfo['open_id'],
            'platform_username' => $userInfo['username'] ?? $userInfo['display_name'],
            'display_name' => $userInfo['display_name'],
            'avatar_url' => $userInfo['avatar_url'] ?? null,
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
            'client_key' => TIKTOK_CLIENT_KEY,
            'client_secret' => TIKTOK_CLIENT_SECRET,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);

        if ($response['status'] !== 200) {
            throw new Exception('Token-Refresh fehlgeschlagen');
        }

        return $response['body'];
    }

    private function exchangeCodeForTokens(string $code, string $codeVerifier): array
    {
        $response = $this->http->post(self::TOKEN_URL, http_build_query([
            'client_key' => TIKTOK_CLIENT_KEY,
            'client_secret' => TIKTOK_CLIENT_SECRET,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => BASE_URL . '/auth/tiktok/callback',
            'code_verifier' => $codeVerifier,
        ]), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);

        if ($response['status'] !== 200) {
            $error = $response['body']['error_description'] ?? $response['body']['error'] ?? 'Token exchange failed';
            error_log('TikTok token exchange failed: ' . print_r($response, true));
            throw new Exception($error);
        }

        return $response['body'];
    }

    private function getUserInfo(string $accessToken): array
    {
        $url = self::USERINFO_URL . '?' . http_build_query([
            'fields' => 'open_id,union_id,avatar_url,display_name,username',
        ]);

        $response = $this->http->get($url, [
            'Authorization' => 'Bearer ' . $accessToken,
        ]);

        if ($response['status'] !== 200) {
            throw new Exception('TikTok Benutzerinfo konnte nicht abgerufen werden');
        }

        return $response['body']['data']['user'];
    }

    private function generateState(): string
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['tiktok_oauth_state'] = $state;
        return $state;
    }

    public function verifyState(string $state): bool
    {
        if (empty($_SESSION['tiktok_oauth_state'])) {
            return false;
        }
        $valid = hash_equals($_SESSION['tiktok_oauth_state'], $state);
        unset($_SESSION['tiktok_oauth_state']);
        return $valid;
    }

    private function generateCodeVerifier(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    private function generateCodeChallenge(string $verifier): string
    {
        return rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');
    }
}
