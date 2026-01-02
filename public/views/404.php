<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seite nicht gefunden | Postamt</title>
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
            flex-direction: column;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 24px;
        }

        nav {
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .logo {
            font-size: 20px;
            font-weight: 700;
            color: #111;
            text-decoration: none;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 60px 24px;
        }

        .error-code {
            font-size: 100px;
            font-weight: 700;
            color: #111;
            line-height: 1;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        p {
            color: #666;
            font-size: 16px;
            margin-bottom: 28px;
            max-width: 400px;
        }

        .btn-primary {
            display: inline-block;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 500;
            border: none;
            border-radius: 6px;
            background: #111;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #333;
        }

        .links {
            margin-top: 40px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .links a {
            color: #666;
            text-decoration: none;
            font-size: 13px;
        }

        .links a:hover {
            color: #111;
        }

        footer {
            padding: 24px;
            text-align: center;
            color: #999;
            font-size: 13px;
            border-top: 1px solid #eee;
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 70px;
            }

            h1 {
                font-size: 22px;
            }

            p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <a href="/" class="logo">Postamt</a>
        </nav>
    </div>

    <div class="content">
        <div class="error-code">404</div>
        <h1>Seite nicht gefunden</h1>
        <p>Die Seite die du suchst existiert nicht oder wurde verschoben. Kein Problem - hier findest du zurueck.</p>
        <a href="/" class="btn-primary">Zur Startseite</a>

        <div class="links">
            <a href="/#features">Features</a>
            <a href="/#pricing">Preise</a>
            <a href="/tools/instagram-scheduler">Instagram Scheduler</a>
            <a href="/tools/tiktok-scheduler">TikTok Scheduler</a>
            <a href="/vergleich/hootsuite-alternative">Hootsuite Alternative</a>
        </div>
    </div>

    <footer>
        <div class="container">
            Gebaut mit Leidenschaft fuer Creator
        </div>
    </footer>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
