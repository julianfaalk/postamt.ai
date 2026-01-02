<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren – Postamt</title>
    <meta name="description" content="Erstelle deinen Postamt Account und starte mit dem einfachsten Social Media Management Tool.">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="https://postamt.ai/register">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #fff;
            color: #111;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            max-width: 380px;
            padding: 24px;
            text-align: center;
        }

        .logo {
            display: inline-block;
            font-size: 20px;
            font-weight: 700;
            color: #111;
            text-decoration: none;
            margin-bottom: 40px;
        }

        .auth-card {
            background: #fafafa;
            border-radius: 12px;
            padding: 32px;
            text-align: left;
        }

        .auth-card h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .auth-card > p {
            color: #666;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 500;
            color: #444;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            font-size: 14px;
            font-family: inherit;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fff;
            color: #111;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            border-color: #111;
        }

        .form-group input::placeholder {
            color: #999;
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 14px;
            font-size: 15px;
            font-weight: 500;
            border: none;
            border-radius: 6px;
            background: #111;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }

        .btn-primary:hover {
            background: #333;
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .error-message {
            margin-top: 16px;
            padding: 12px;
            font-size: 13px;
            color: #dc2626;
            background: #fef2f2;
            border-radius: 6px;
        }

        .error-message:empty {
            display: none;
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fff;
            color: #333;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .btn-google:hover {
            background: #f5f5f5;
            border-color: #ccc;
        }

        .btn-google svg {
            width: 18px;
            height: 18px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: #999;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #ddd;
        }

        .divider span {
            padding: 0 12px;
        }

        .auth-switch {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }

        .auth-switch a {
            color: #111;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-switch a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <a href="/" class="logo">Postamt</a>

        <div class="auth-card">
            <h1>Account erstellen</h1>
            <p>Starte jetzt mit Postamt</p>

            <a href="/auth/google" class="btn-google">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Mit Google registrieren
            </a>

            <div class="divider"><span>oder</span></div>

            <form id="register-form" onsubmit="handleRegister(event)">
                <div class="form-group">
                    <label for="name">Name (optional)</label>
                    <input type="text" id="name" name="name" placeholder="Dein Name">
                </div>

                <div class="form-group">
                    <label for="email">E-Mail</label>
                    <input type="email" id="email" name="email" required placeholder="deine@email.de">
                </div>

                <div class="form-group">
                    <label for="password">Passwort</label>
                    <input type="password" id="password" name="password" required placeholder="Mindestens 8 Zeichen" minlength="8">
                </div>

                <button type="submit" class="btn-primary" id="submit-btn">Registrieren</button>

                <div class="error-message" id="error-message"></div>
            </form>

            <p class="auth-switch">
                Bereits registriert? <a href="/login">Jetzt anmelden</a>
            </p>
        </div>
    </div>

    <script>
        // Show error from URL parameter (e.g., from OAuth redirect)
        const urlParams = new URLSearchParams(window.location.search);
        const urlError = urlParams.get('error');
        if (urlError) {
            document.getElementById('error-message').textContent = decodeURIComponent(urlError);
            // Clean URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        async function handleRegister(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = document.getElementById('submit-btn');
            const error = document.getElementById('error-message');

            btn.disabled = true;
            btn.textContent = 'Wird registriert...';
            error.textContent = '';

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = '/dashboard';
                } else {
                    error.textContent = data.error || 'Registrierung fehlgeschlagen';
                }
            } catch (err) {
                error.textContent = 'Verbindungsfehler. Bitte versuche es erneut.';
            }

            btn.disabled = false;
            btn.textContent = 'Registrieren';
        }
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="Analytics" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
