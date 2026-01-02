<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Postamt</title>
    <meta name="description" content="Melde dich bei Postamt an und verwalte deine Social Media Accounts an einem Ort.">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="https://postamt.ai/login">
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
            display: none;
        }

        .error-message:not(:empty) {
            display: block;
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
            <h1>Willkommen zurueck</h1>
            <p>Melde dich an um fortzufahren</p>

            <form id="login-form" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label for="email">E-Mail</label>
                    <input type="email" id="email" name="email" required placeholder="deine@email.de">
                </div>

                <div class="form-group">
                    <label for="password">Passwort</label>
                    <input type="password" id="password" name="password" required placeholder="Dein Passwort">
                </div>

                <button type="submit" class="btn-primary" id="submit-btn">Anmelden</button>

                <div class="error-message" id="error-message"></div>
            </form>

            <p class="auth-switch">
                Noch kein Account? <a href="/register">Jetzt registrieren</a>
            </p>
        </div>
    </div>

    <script>
        async function handleLogin(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = document.getElementById('submit-btn');
            const error = document.getElementById('error-message');

            btn.disabled = true;
            btn.textContent = 'Wird angemeldet...';
            error.textContent = '';

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = '/dashboard';
                } else {
                    error.textContent = data.error || 'Login fehlgeschlagen';
                }
            } catch (err) {
                error.textContent = 'Verbindungsfehler. Bitte versuche es erneut.';
            }

            btn.disabled = false;
            btn.textContent = 'Anmelden';
        }
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="Analytics" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
