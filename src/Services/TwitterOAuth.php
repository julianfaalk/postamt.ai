<?php
/**
 * Twitter/X OAuth 2.0 Service
 * Handles authentication and posting to Twitter/X
 */

class TwitterOAuth
{
    private const AUTH_URL = 'https://twitter.com/i/oauth2/authorize';
    private const TOKEN_URL = 'https://api.twitter.com/2/oauth2/token';
    private const USERINFO_URL = 'https://api.twitter.com/2/users/me';
    private const TWEET_URL = 'https://api.twitter.com/2/tweets';

    private HttpClient $http;

    public function __construct()
    {
        $this->http = new HttpClient();
    }

    /**
     * Generate Twitter OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        $state = $this->generateState();
        $codeVerifier = $this->generateCodeVerifier();
        $codeChallenge = $this->generateCodeChallenge($codeVerifier);

        // Store code verifier for token exchange
        $_SESSION['twitter_code_verifier'] = $codeVerifier;

        $params = [
            'response_type' => 'code',
            'client_id' => TWITTER_CLIENT_ID,
            'redirect_uri' => BASE_URL . '/auth/twitter/callback',
            'scope' => 'tweet.read tweet.write users.read offline.access',
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
        $codeVerifier = $_SESSION['twitter_code_verifier'] ?? '';
        unset($_SESSION['twitter_code_verifier']);

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
            [$userId, 'twitter', $userInfo['id']]
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
            'platform' => 'twitter',
            'platform_user_id' => $userInfo['id'],
            'platform_username' => $userInfo['username'],
            'display_name' => $userInfo['name'] ?? $userInfo['username'],
            'avatar_url' => $userInfo['profile_image_url'] ?? null,
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
     * Post a tweet
     */
    public function postTweet(string $accessToken, string $text): array
    {
        $response = $this->http->post(self::TWEET_URL, [
            'text' => $text,
        ], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ]);

        if ($response['status'] !== 201) {
            $error = $response['body']['detail'] ?? $response['body']['title'] ?? 'Tweet konnte nicht gepostet werden';
            error_log('Twitter post failed: ' . print_r($response, true));
            throw new Exception($error);
        }

        return $response['body']['data'];
    }

    /**
     * Refresh access token
     */
    public function refreshToken(string $refreshToken): array
    {
        $response = $this->http->post(self::TOKEN_URL, http_build_query([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => TWITTER_CLIENT_ID,
        ]), [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode(TWITTER_CLIENT_ID . ':' . TWITTER_CLIENT_SECRET),
        ]);

        if ($response['status'] !== 200) {
            throw new Exception('Token-Refresh fehlgeschlagen');
        }

        return $response['body'];
    }

    private function exchangeCodeForTokens(string $code, string $codeVerifier): array
    {
        $response = $this->http->post(self::TOKEN_URL, http_build_query([
            'code' => $code,
            'grant_type' => 'authorization_code',
            'client_id' => TWITTER_CLIENT_ID,
            'redirect_uri' => BASE_URL . '/auth/twitter/callback',
            'code_verifier' => $codeVerifier,
        ]), [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode(TWITTER_CLIENT_ID . ':' . TWITTER_CLIENT_SECRET),
        ]);

        if ($response['status'] !== 200) {
            $error = $response['body']['error_description'] ?? $response['body']['error'] ?? 'Token exchange failed';
            error_log('Twitter token exchange failed: ' . print_r($response, true));
            throw new Exception($error);
        }

        return $response['body'];
    }

    private function getUserInfo(string $accessToken): array
    {
        $response = $this->http->get(self::USERINFO_URL . '?user.fields=profile_image_url', [
            'Authorization' => 'Bearer ' . $accessToken,
        ]);

        if ($response['status'] !== 200) {
            throw new Exception('Benutzerinfo konnte nicht abgerufen werden');
        }

        return $response['body']['data'];
    }

    private function generateState(): string
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['twitter_oauth_state'] = $state;
        return $state;
    }

    public function verifyState(string $state): bool
    {
        if (empty($_SESSION['twitter_oauth_state'])) {
            return false;
        }
        $valid = hash_equals($_SESSION['twitter_oauth_state'], $state);
        unset($_SESSION['twitter_oauth_state']);
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
