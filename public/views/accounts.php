<?php $user = Auth::user(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts – Postamt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <div class="app-layout">
        <?php include __DIR__ . '/_sidebar.php'; ?>

        <main class="main-content">
            <header class="page-header">
                <div>
                    <h1>Social Accounts</h1>
                    <p>Verbinde deine Social Media Accounts</p>
                </div>
            </header>

            <div class="account-grid" id="accounts-grid">
                <!-- Connected accounts will be loaded here -->

                <div class="connect-card" onclick="connectAccount('twitter')">
                    <div class="connect-icon">&#120143;</div>
                    <h3>X / Twitter verbinden</h3>
                    <p>Poste Tweets und Threads</p>
                </div>

                <div class="connect-card" onclick="connectAccount('instagram')">
                    <div class="connect-icon">&#128247;</div>
                    <h3>Instagram verbinden</h3>
                    <p>Posts, Stories und Reels</p>
                </div>

                <div class="connect-card" onclick="connectAccount('tiktok')">
                    <div class="connect-icon">&#127925;</div>
                    <h3>TikTok verbinden</h3>
                    <p>Videos posten</p>
                </div>

                <div class="connect-card" onclick="connectAccount('linkedin')">
                    <div class="connect-icon">&#128188;</div>
                    <h3>LinkedIn verbinden</h3>
                    <p>Professional Content</p>
                </div>

                <div class="connect-card" onclick="connectAccount('youtube')">
                    <div class="connect-icon">&#9654;&#65039;</div>
                    <h3>YouTube verbinden</h3>
                    <p>Videos und Shorts</p>
                </div>
            </div>

            <div style="margin-top: 40px; padding: 24px; background: #18181b; border: 1px solid #27272a; border-radius: 16px;">
                <h3 style="margin-bottom: 12px;">Hinweis</h3>
                <p style="color: #a1a1aa; font-size: 14px;">
                    Die Verbindung zu Social Media Plattformen erfordert API-Zugangsdaten.
                    Diese werden in einer spaeteren Version hinzugefuegt.
                    Aktuell ist dies eine Demo-Ansicht.
                </p>
            </div>
        </main>
    </div>

    <script>
        function connectAccount(platform) {
            alert('Verbindung zu ' + platform + ' wird bald verfuegbar sein!');
        }

        async function logout() {
            await fetch('/api/auth/logout', { method: 'POST' });
            window.location.href = '/';
        }
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
