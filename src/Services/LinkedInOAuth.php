<?php
/**
 * LinkedIn OAuth 2.0 Service
 * Handles authentication and posting to LinkedIn
 */

class LinkedInOAuth
{
    private const AUTH_URL = 'https://www.linkedin.com/oauth/v2/authorization';
    private const TOKEN_URL = 'https://www.linkedin.com/oauth/v2/accessToken';
    private const USERINFO_URL = 'https://api.linkedin.com/v2/userinfo';
    private const POST_URL = 'https://api.linkedin.com/v2/ugcPosts';

    private HttpClient $http;

    public function __construct()
    {
        $this->http = new HttpClient();
    }

    /**
     * Generate LinkedIn OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        $state = $this->generateState();

        $params = [
            'response_type' => 'code',
            'client_id' => LINKEDIN_CLIENT_ID,
            'redirect_uri' => BASE_URL . '/auth/linkedin/callback',
            'scope' => 'openid profile email w_member_social',
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

        // Get user info
        $userInfo = $this->getUserInfo($tokens['access_token']);

        // Check if account already connected
        $existing = Database::fetch(
            'SELECT id FROM accounts WHERE user_id = ? AND platform = ? AND platform_user_id = ?',
            [$userId, 'linkedin', $userInfo['sub']]
        );

        if ($existing) {
            // Update existing account
            Database::update('accounts', [
                'access_token' => $tokens['access_token'],
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
            'platform' => 'linkedin',
            'platform_user_id' => $userInfo['sub'],
            'platform_username' => $userInfo['email'] ?? $userInfo['name'],
            'display_name' => $userInfo['name'] ?? null,
            'avatar_url' => $userInfo['picture'] ?? null,
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
     * Post to LinkedIn
     */
    public function createPost(string $accessToken, string $personUrn, string $text): array
    {
        $postData = [
            'author' => $personUrn,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $text,
                    ],
                    'shareMediaCategory' => 'NONE',
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        $response = $this->http->post(self::POST_URL, $postData, [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'X-Restli-Protocol-Version' => '2.0.0',
        ]);

        if ($response['status'] !== 201) {
            $error = $response['body']['message'] ?? 'LinkedIn Post konnte nicht erstellt werden';
            error_log('LinkedIn post failed: ' . print_r($response, true));
            throw new Exception($error);
        }

        return [
            'id' => $response['body']['id'] ?? null,
        ];
    }

    private function exchangeCodeForTokens(string $code): array
    {
        $response = $this->http->post(self::TOKEN_URL, http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => LINKEDIN_CLIENT_ID,
            'client_secret' => LINKEDIN_CLIENT_SECRET,
            'redirect_uri' => BASE_URL . '/auth/linkedin/callback',
        ]), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);

        if ($response['status'] !== 200) {
            $error = $response['body']['error_description'] ?? $response['body']['error'] ?? 'Token exchange failed';
            error_log('LinkedIn token exchange failed: ' . print_r($response, true));
            throw new Exception($error);
        }

        return $response['body'];
    }

    private function getUserInfo(string $accessToken): array
    {
        $response = $this->http->get(self::USERINFO_URL, [
            'Authorization' => 'Bearer ' . $accessToken,
        ]);

        if ($response['status'] !== 200) {
            throw new Exception('LinkedIn Benutzerinfo konnte nicht abgerufen werden');
        }

        return $response['body'];
    }

    private function generateState(): string
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['linkedin_oauth_state'] = $state;
        return $state;
    }

    public function verifyState(string $state): bool
    {
        if (empty($_SESSION['linkedin_oauth_state'])) {
            return false;
        }
        $valid = hash_equals($_SESSION['linkedin_oauth_state'], $state);
        unset($_SESSION['linkedin_oauth_state']);
        return $valid;
    }
}
