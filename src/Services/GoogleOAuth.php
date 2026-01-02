<?php
/**
 * Google OAuth 2.0 Service
 * Handles authentication via Google/Gmail accounts
 */

class GoogleOAuth
{
    private const AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const USERINFO_URL = 'https://www.googleapis.com/oauth2/v2/userinfo';

    private HttpClient $http;

    public function __construct()
    {
        $this->http = new HttpClient();
    }

    /**
     * Generate the Google OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        $state = $this->generateState();

        $params = [
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope' => 'email profile openid',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
        ];

        return self::AUTH_URL . '?' . http_build_query($params);
    }

    /**
     * Handle the OAuth callback and get user info
     */
    public function handleCallback(string $code): array
    {
        // Exchange code for tokens
        $tokens = $this->exchangeCodeForTokens($code);

        if (empty($tokens['access_token'])) {
            throw new Exception('Token-Austausch fehlgeschlagen');
        }

        // Get user info from Google
        $userInfo = $this->getUserInfo($tokens['access_token']);

        if (empty($userInfo['email'])) {
            throw new Exception('E-Mail konnte nicht abgerufen werden');
        }

        return [
            'google_id' => $userInfo['id'],
            'email' => $userInfo['email'],
            'name' => $userInfo['name'] ?? null,
            'picture' => $userInfo['picture'] ?? null,
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'] ?? null,
            'expires_in' => $tokens['expires_in'] ?? null,
        ];
    }

    /**
     * Exchange authorization code for access token
     */
    private function exchangeCodeForTokens(string $code): array
    {
        $response = $this->http->post(self::TOKEN_URL, http_build_query([
            'code' => $code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code',
        ]), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);

        if ($response['status'] !== 200) {
            $error = $response['body']['error_description'] ?? 'Token exchange failed';
            throw new Exception($error);
        }

        return $response['body'];
    }

    /**
     * Get user information from Google API
     */
    private function getUserInfo(string $accessToken): array
    {
        $response = $this->http->get(self::USERINFO_URL, [
            'Authorization' => 'Bearer ' . $accessToken,
        ]);

        if ($response['status'] !== 200) {
            throw new Exception('Benutzerinformationen konnten nicht abgerufen werden');
        }

        return $response['body'];
    }

    /**
     * Generate and store a CSRF state token
     */
    private function generateState(): string
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;
        return $state;
    }

    /**
     * Verify the state parameter to prevent CSRF
     */
    public function verifyState(string $state): bool
    {
        if (empty($_SESSION['oauth_state'])) {
            return false;
        }

        $valid = hash_equals($_SESSION['oauth_state'], $state);

        // Clear state after verification
        unset($_SESSION['oauth_state']);

        return $valid;
    }

    /**
     * Find or create a user from Google OAuth data
     */
    public static function findOrCreateUser(array $googleUser): array
    {
        // Check if user exists by google_id
        $user = Database::fetch(
            'SELECT * FROM users WHERE google_id = ?',
            [$googleUser['google_id']]
        );

        if ($user) {
            // Update avatar if changed
            if ($googleUser['picture'] && $user['avatar_url'] !== $googleUser['picture']) {
                Database::update('users', [
                    'avatar_url' => $googleUser['picture'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ], 'id = ?', [$user['id']]);
            }
            return $user;
        }

        // Check if user exists by email (for linking accounts)
        $user = Database::fetch(
            'SELECT * FROM users WHERE email = ?',
            [$googleUser['email']]
        );

        if ($user) {
            // Link Google account to existing user
            Database::update('users', [
                'google_id' => $googleUser['google_id'],
                'avatar_url' => $googleUser['picture'] ?? $user['avatar_url'],
                'updated_at' => date('Y-m-d H:i:s'),
            ], 'id = ?', [$user['id']]);

            $user['google_id'] = $googleUser['google_id'];
            return $user;
        }

        // Create new user
        $userId = Database::insert('users', [
            'email' => $googleUser['email'],
            'name' => $googleUser['name'],
            'google_id' => $googleUser['google_id'],
            'avatar_url' => $googleUser['picture'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return Database::fetch('SELECT * FROM users WHERE id = ?', [$userId]);
    }
}
