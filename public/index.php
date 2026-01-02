<?php
/**
 * Application Entry Point
 */

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Load configuration
require_once __DIR__ . '/../src/config.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../src/Lib/' . $class . '.php',
        __DIR__ . '/../src/Controllers/' . $class . '.php',
        __DIR__ . '/../src/Models/' . $class . '.php',
        __DIR__ . '/../src/Services/' . $class . '.php',
        __DIR__ . '/../src/Publishers/' . $class . '.php',
        __DIR__ . '/../src/Validators/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

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
