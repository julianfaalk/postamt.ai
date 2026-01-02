<?php
/**
 * Application Entry Point
 */

// Load application bootstrap
require_once __DIR__ . '/../src/bootstrap.php';

// Initialize session
Auth::start();

// Create router
$router = new Router();

// =====================================
// API Routes
// =====================================

// Waitlist endpoints
$router->post('/api/waitlist', function () {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        http_response_code(400);
        return ['error' => 'Ungueltige E-Mail-Adresse'];
    }

    // Check if already exists
    $existing = Database::fetch('SELECT id FROM waitlist WHERE email = ?', [$email]);
    if ($existing) {
        http_response_code(409);
        return ['error' => 'Du bist bereits auf der Warteliste'];
    }

    // Insert
    Database::insert('waitlist', [
        'email' => $email,
        'created_at' => date('Y-m-d H:i:s'),
    ]);

    return ['success' => true, 'message' => 'Erfolgreich zur Warteliste hinzugefuegt'];
});

$router->get('/api/waitlist/count', function () {
    $result = Database::fetch('SELECT COUNT(*) as count FROM waitlist');
    return ['count' => (int) $result['count']];
});

// Auth endpoints
$router->post('/api/auth/register', function () {
    $input = json_decode(file_get_contents('php://input'), true);

    $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $input['password'] ?? '';
    $name = trim($input['name'] ?? '');

    if (!$email) {
        http_response_code(400);
        return ['error' => 'Ungueltige E-Mail-Adresse'];
    }

    if (strlen($password) < 8) {
        http_response_code(400);
        return ['error' => 'Passwort muss mindestens 8 Zeichen haben'];
    }

    // Check if email exists
    $existing = Database::fetch('SELECT id FROM users WHERE email = ?', [$email]);
    if ($existing) {
        http_response_code(409);
        return ['error' => 'E-Mail bereits registriert'];
    }

    // Create user
    $userId = Database::insert('users', [
        'email' => $email,
        'password_hash' => Auth::hashPassword($password),
        'name' => $name ?: null,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    $user = Database::fetch('SELECT id, email, name FROM users WHERE id = ?', [$userId]);
    Auth::login($user);

    return ['success' => true, 'user' => $user];
});

$router->post('/api/auth/login', function () {
    $input = json_decode(file_get_contents('php://input'), true);

    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    $user = Database::fetch('SELECT * FROM users WHERE email = ?', [$email]);

    if (!$user || !Auth::verifyPassword($password, $user['password_hash'])) {
        http_response_code(401);
        return ['error' => 'Ungueltige Anmeldedaten'];
    }

    Auth::login($user);

    return [
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
        ],
    ];
});

$router->post('/api/auth/logout', function () {
    Auth::logout();
    return ['success' => true];
});

$router->get('/api/auth/me', function () {
    $user = Auth::user();
    if (!$user) {
        http_response_code(401);
        return ['error' => 'Nicht angemeldet'];
    }
    return ['user' => $user];
});

// =====================================
// Google OAuth Routes
// =====================================

// Initiate Google OAuth login
$router->get('/auth/google', function () {
    // Check if Google OAuth is configured
    if (empty(GOOGLE_CLIENT_ID) || empty(GOOGLE_CLIENT_SECRET)) {
        header('Location: /login?error=' . urlencode('Google OAuth ist nicht konfiguriert'));
        exit;
    }

    $oauth = new GoogleOAuth();
    header('Location: ' . $oauth->getAuthUrl());
    exit;
});

// Google OAuth callback
$router->get('/auth/google/callback', function () {
    $code = $_GET['code'] ?? '';
    $state = $_GET['state'] ?? '';
    $error = $_GET['error'] ?? '';

    // Handle OAuth errors
    if ($error) {
        $message = match($error) {
            'access_denied' => 'Zugriff verweigert',
            default => 'OAuth-Fehler: ' . $error,
        };
        header('Location: /login?error=' . urlencode($message));
        exit;
    }

    if (empty($code)) {
        header('Location: /login?error=' . urlencode('Kein Autorisierungscode erhalten'));
        exit;
    }

    $oauth = new GoogleOAuth();

    // Verify state to prevent CSRF
    if (!$oauth->verifyState($state)) {
        header('Location: /login?error=' . urlencode('Ungueltige Anfrage (CSRF-Schutz)'));
        exit;
    }

    try {
        // Exchange code for user info
        $googleUser = $oauth->handleCallback($code);

        // Find or create user
        $user = GoogleOAuth::findOrCreateUser($googleUser);

        // Log user in
        Auth::login($user);

        // Redirect to dashboard
        header('Location: /dashboard');
        exit;

    } catch (Exception $e) {
        error_log('Google OAuth error: ' . $e->getMessage());
        header('Location: /login?error=' . urlencode('Anmeldung fehlgeschlagen: ' . $e->getMessage()));
        exit;
    }
});

// =====================================
// Posts API
// =====================================

// Create post
$router->post('/api/posts', function () {
    Auth::require();
    $userId = Auth::userId();
    $input = json_decode(file_get_contents('php://input'), true);

    $content = trim($input['content'] ?? '');
    if (empty($content)) {
        http_response_code(400);
        return ['error' => 'Post-Inhalt darf nicht leer sein'];
    }

    $scheduledAt = null;
    $status = 'draft';

    if (!empty($input['scheduled_at'])) {
        $scheduledAt = date('Y-m-d H:i:s', strtotime($input['scheduled_at']));
        $status = 'scheduled';
    } elseif (!empty($input['publish_now'])) {
        $status = 'queued';
        $scheduledAt = date('Y-m-d H:i:s');
    }

    $postId = Database::insert('posts', [
        'user_id' => $userId,
        'content' => $content,
        'media_urls' => json_encode($input['media'] ?? []),
        'status' => $status,
        'scheduled_at' => $scheduledAt,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    // Add platform assignments
    $platforms = $input['platforms'] ?? [];
    foreach ($platforms as $accountId) {
        Database::insert('post_platforms', [
            'post_id' => $postId,
            'account_id' => (int)$accountId,
            'status' => 'pending',
        ]);
    }

    $post = Database::fetch('SELECT * FROM posts WHERE id = ?', [$postId]);
    return ['success' => true, 'post' => $post];
});

// List posts
$router->get('/api/posts', function () {
    Auth::require();
    $userId = Auth::userId();

    $status = $_GET['status'] ?? null;
    $limit = min((int)($_GET['limit'] ?? 50), 100);
    $offset = (int)($_GET['offset'] ?? 0);

    $sql = 'SELECT * FROM posts WHERE user_id = ?';
    $params = [$userId];

    if ($status) {
        $sql .= ' AND status = ?';
        $params[] = $status;
    }

    $sql .= ' ORDER BY created_at DESC LIMIT ? OFFSET ?';
    $params[] = $limit;
    $params[] = $offset;

    $posts = Database::fetchAll($sql, $params);

    return ['posts' => $posts];
});

// Get single post
$router->get('/api/posts/{id}', function ($params) {
    Auth::require();
    $userId = Auth::userId();

    $post = Database::fetch(
        'SELECT * FROM posts WHERE id = ? AND user_id = ?',
        [$params['id'], $userId]
    );

    if (!$post) {
        http_response_code(404);
        return ['error' => 'Post nicht gefunden'];
    }

    // Get platform assignments
    $post['platforms'] = Database::fetchAll(
        'SELECT pp.*, a.platform, a.platform_username
         FROM post_platforms pp
         JOIN accounts a ON pp.account_id = a.id
         WHERE pp.post_id = ?',
        [$post['id']]
    );

    return ['post' => $post];
});

// Update post
$router->post('/api/posts/{id}', function ($params) {
    Auth::require();
    $userId = Auth::userId();
    $input = json_decode(file_get_contents('php://input'), true);

    $post = Database::fetch(
        'SELECT * FROM posts WHERE id = ? AND user_id = ?',
        [$params['id'], $userId]
    );

    if (!$post) {
        http_response_code(404);
        return ['error' => 'Post nicht gefunden'];
    }

    if (in_array($post['status'], ['publishing', 'published'])) {
        http_response_code(400);
        return ['error' => 'Post kann nicht mehr bearbeitet werden'];
    }

    $updates = ['updated_at' => date('Y-m-d H:i:s')];

    if (isset($input['content'])) {
        $updates['content'] = trim($input['content']);
    }
    if (isset($input['scheduled_at'])) {
        $updates['scheduled_at'] = date('Y-m-d H:i:s', strtotime($input['scheduled_at']));
        $updates['status'] = 'scheduled';
    }
    if (isset($input['status'])) {
        $updates['status'] = $input['status'];
    }

    Database::update('posts', $updates, 'id = ?', [$params['id']]);

    $post = Database::fetch('SELECT * FROM posts WHERE id = ?', [$params['id']]);
    return ['success' => true, 'post' => $post];
});

// Delete post
$router->post('/api/posts/{id}/delete', function ($params) {
    Auth::require();
    $userId = Auth::userId();

    $post = Database::fetch(
        'SELECT * FROM posts WHERE id = ? AND user_id = ?',
        [$params['id'], $userId]
    );

    if (!$post) {
        http_response_code(404);
        return ['error' => 'Post nicht gefunden'];
    }

    Database::delete('posts', 'id = ?', [$params['id']]);
    return ['success' => true];
});

// =====================================
// Accounts API
// =====================================

// List connected accounts
$router->get('/api/accounts', function () {
    Auth::require();
    $userId = Auth::userId();

    $accounts = Database::fetchAll(
        'SELECT id, platform, platform_username, display_name, avatar_url, is_active, created_at
         FROM accounts WHERE user_id = ? ORDER BY created_at DESC',
        [$userId]
    );

    return ['accounts' => $accounts];
});

// Disconnect account
$router->post('/api/accounts/{id}/disconnect', function ($params) {
    Auth::require();
    $userId = Auth::userId();

    $account = Database::fetch(
        'SELECT * FROM accounts WHERE id = ? AND user_id = ?',
        [$params['id'], $userId]
    );

    if (!$account) {
        http_response_code(404);
        return ['error' => 'Account nicht gefunden'];
    }

    Database::delete('accounts', 'id = ?', [$params['id']]);
    return ['success' => true];
});

// =====================================
// Twitter/X OAuth
// =====================================

$router->get('/auth/twitter', function () {
    Auth::require();

    if (empty(TWITTER_CLIENT_ID) || empty(TWITTER_CLIENT_SECRET)) {
        header('Location: /accounts?error=' . urlencode('Twitter API nicht konfiguriert'));
        exit;
    }

    $twitter = new TwitterOAuth();
    header('Location: ' . $twitter->getAuthUrl());
    exit;
});

$router->get('/auth/twitter/callback', function () {
    Auth::require();
    $userId = Auth::userId();

    $code = $_GET['code'] ?? '';
    $state = $_GET['state'] ?? '';
    $error = $_GET['error'] ?? '';

    if ($error) {
        header('Location: /accounts?error=' . urlencode('Twitter-Verbindung abgelehnt'));
        exit;
    }

    $twitter = new TwitterOAuth();

    if (!$twitter->verifyState($state)) {
        header('Location: /accounts?error=' . urlencode('Ungueltige Anfrage'));
        exit;
    }

    try {
        $account = $twitter->handleCallback($code, $userId);
        header('Location: /accounts?success=' . urlencode('Twitter verbunden: @' . $account['platform_username']));
    } catch (Exception $e) {
        error_log('Twitter OAuth error: ' . $e->getMessage());
        header('Location: /accounts?error=' . urlencode('Verbindung fehlgeschlagen: ' . $e->getMessage()));
    }
    exit;
});

// =====================================
// LinkedIn OAuth
// =====================================

$router->get('/auth/linkedin', function () {
    Auth::require();

    if (empty(LINKEDIN_CLIENT_ID) || empty(LINKEDIN_CLIENT_SECRET)) {
        header('Location: /accounts?error=' . urlencode('LinkedIn API nicht konfiguriert'));
        exit;
    }

    $linkedin = new LinkedInOAuth();
    header('Location: ' . $linkedin->getAuthUrl());
    exit;
});

$router->get('/auth/linkedin/callback', function () {
    Auth::require();
    $userId = Auth::userId();

    $code = $_GET['code'] ?? '';
    $state = $_GET['state'] ?? '';
    $error = $_GET['error'] ?? '';

    if ($error) {
        header('Location: /accounts?error=' . urlencode('LinkedIn-Verbindung abgelehnt'));
        exit;
    }

    $linkedin = new LinkedInOAuth();

    if (!$linkedin->verifyState($state)) {
        header('Location: /accounts?error=' . urlencode('Ungueltige Anfrage'));
        exit;
    }

    try {
        $account = $linkedin->handleCallback($code, $userId);
        header('Location: /accounts?success=' . urlencode('LinkedIn verbunden: ' . $account['display_name']));
    } catch (Exception $e) {
        error_log('LinkedIn OAuth error: ' . $e->getMessage());
        header('Location: /accounts?error=' . urlencode('Verbindung fehlgeschlagen: ' . $e->getMessage()));
    }
    exit;
});

// =====================================
// YouTube OAuth
// =====================================

$router->get('/auth/youtube', function () {
    Auth::require();

    if (empty(YOUTUBE_CLIENT_ID) || empty(YOUTUBE_CLIENT_SECRET)) {
        header('Location: /accounts?error=' . urlencode('YouTube API nicht konfiguriert'));
        exit;
    }

    $youtube = new YouTubeOAuth();
    header('Location: ' . $youtube->getAuthUrl());
    exit;
});

$router->get('/auth/youtube/callback', function () {
    Auth::require();
    $userId = Auth::userId();

    $code = $_GET['code'] ?? '';
    $state = $_GET['state'] ?? '';
    $error = $_GET['error'] ?? '';

    if ($error) {
        header('Location: /accounts?error=' . urlencode('YouTube-Verbindung abgelehnt'));
        exit;
    }

    $youtube = new YouTubeOAuth();

    if (!$youtube->verifyState($state)) {
        header('Location: /accounts?error=' . urlencode('Ungueltige Anfrage'));
        exit;
    }

    try {
        $account = $youtube->handleCallback($code, $userId);
        header('Location: /accounts?success=' . urlencode('YouTube verbunden: ' . $account['display_name']));
    } catch (Exception $e) {
        error_log('YouTube OAuth error: ' . $e->getMessage());
        header('Location: /accounts?error=' . urlencode('Verbindung fehlgeschlagen: ' . $e->getMessage()));
    }
    exit;
});

// =====================================
// Instagram OAuth (Meta)
// =====================================

$router->get('/auth/instagram', function () {
    Auth::require();

    if (empty(INSTAGRAM_CLIENT_ID) || empty(INSTAGRAM_CLIENT_SECRET)) {
        header('Location: /accounts?error=' . urlencode('Instagram API nicht konfiguriert'));
        exit;
    }

    $instagram = new InstagramOAuth();
    header('Location: ' . $instagram->getAuthUrl());
    exit;
});

$router->get('/auth/instagram/callback', function () {
    Auth::require();
    $userId = Auth::userId();

    $code = $_GET['code'] ?? '';
    $state = $_GET['state'] ?? '';
    $error = $_GET['error'] ?? '';

    if ($error) {
        header('Location: /accounts?error=' . urlencode('Instagram-Verbindung abgelehnt'));
        exit;
    }

    $instagram = new InstagramOAuth();

    if (!$instagram->verifyState($state)) {
        header('Location: /accounts?error=' . urlencode('Ungueltige Anfrage'));
        exit;
    }

    try {
        $account = $instagram->handleCallback($code, $userId);
        header('Location: /accounts?success=' . urlencode('Instagram verbunden: @' . $account['platform_username']));
    } catch (Exception $e) {
        error_log('Instagram OAuth error: ' . $e->getMessage());
        header('Location: /accounts?error=' . urlencode('Verbindung fehlgeschlagen: ' . $e->getMessage()));
    }
    exit;
});

// =====================================
// TikTok OAuth
// =====================================

$router->get('/auth/tiktok', function () {
    Auth::require();

    if (empty(TIKTOK_CLIENT_KEY) || empty(TIKTOK_CLIENT_SECRET)) {
        header('Location: /accounts?error=' . urlencode('TikTok API nicht konfiguriert'));
        exit;
    }

    $tiktok = new TikTokOAuth();
    header('Location: ' . $tiktok->getAuthUrl());
    exit;
});

$router->get('/auth/tiktok/callback', function () {
    Auth::require();
    $userId = Auth::userId();

    $code = $_GET['code'] ?? '';
    $state = $_GET['state'] ?? '';
    $error = $_GET['error'] ?? '';

    if ($error) {
        header('Location: /accounts?error=' . urlencode('TikTok-Verbindung abgelehnt'));
        exit;
    }

    $tiktok = new TikTokOAuth();

    if (!$tiktok->verifyState($state)) {
        header('Location: /accounts?error=' . urlencode('Ungueltige Anfrage'));
        exit;
    }

    try {
        $account = $tiktok->handleCallback($code, $userId);
        header('Location: /accounts?success=' . urlencode('TikTok verbunden: @' . $account['platform_username']));
    } catch (Exception $e) {
        error_log('TikTok OAuth error: ' . $e->getMessage());
        header('Location: /accounts?error=' . urlencode('Verbindung fehlgeschlagen: ' . $e->getMessage()));
    }
    exit;
});

// =====================================
// Page Routes
// =====================================

$router->get('/', function () {
    include __DIR__ . '/views/landing.php';
});

$router->get('/login', function () {
    if (Auth::check()) {
        header('Location: /dashboard');
        exit;
    }
    include __DIR__ . '/views/login.php';
});

$router->get('/register', function () {
    if (Auth::check()) {
        header('Location: /dashboard');
        exit;
    }
    include __DIR__ . '/views/register.php';
});

$router->get('/dashboard', function () {
    if (!Auth::check()) {
        header('Location: /login');
        exit;
    }
    include __DIR__ . '/views/dashboard.php';
});

$router->get('/compose', function () {
    if (!Auth::check()) {
        header('Location: /login');
        exit;
    }
    include __DIR__ . '/views/compose.php';
});

$router->get('/calendar', function () {
    if (!Auth::check()) {
        header('Location: /login');
        exit;
    }
    include __DIR__ . '/views/calendar.php';
});

$router->get('/accounts', function () {
    if (!Auth::check()) {
        header('Location: /login');
        exit;
    }
    include __DIR__ . '/views/accounts.php';
});

$router->get('/analytics', function () {
    if (!Auth::check()) {
        header('Location: /login');
        exit;
    }
    include __DIR__ . '/views/analytics.php';
});

$router->get('/templates', function () {
    if (!Auth::check()) {
        header('Location: /login');
        exit;
    }
    include __DIR__ . '/views/templates.php';
});

$router->get('/settings', function () {
    if (!Auth::check()) {
        header('Location: /login');
        exit;
    }
    include __DIR__ . '/views/settings.php';
});

// =====================================
// SEO Landing Pages
// =====================================

$router->get('/tools/{slug}', function ($params) {
    renderSeoPage('/tools/' . $params['slug']);
});

$router->get('/vergleich/{slug}', function ($params) {
    renderSeoPage('/vergleich/' . $params['slug']);
});

$router->get('/fuer/{slug}', function ($params) {
    renderSeoPage('/fuer/' . $params['slug']);
});

$router->get('/guides/{slug}', function ($params) {
    renderSeoPage('/guides/' . $params['slug']);
});

/**
 * Render an SEO page from the database
 */
function renderSeoPage($slug) {
    $page = Database::fetch('SELECT * FROM seo_pages WHERE slug = ?', [$slug]);

    if (!$page) {
        http_response_code(404);
        include __DIR__ . '/views/404.php';
        return;
    }

    // Get related pages for internal linking
    $relatedPages = Database::fetchAll(
        'SELECT slug, title, intro_text as description FROM seo_pages WHERE page_type = ? AND slug != ? ORDER BY RANDOM() LIMIT 4',
        [$page['page_type'], $slug]
    );

    $page['related_pages_json'] = json_encode(array_map(function ($p) {
        return [
            'slug' => $p['slug'],
            'title' => $p['title'],
            'description' => substr($p['description'], 0, 100) . '...'
        ];
    }, $relatedPages));

    include __DIR__ . '/views/seo-page.php';
}

// Dispatch request
$router->dispatch();
