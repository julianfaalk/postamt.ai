<?php $user = Auth::user(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics – Postamt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <div class="app-layout">
        <?php include __DIR__ . '/_sidebar.php'; ?>

        <main class="main-content">
            <header class="page-header">
                <div>
                    <h1>Analytics</h1>
                    <p>Verstehe was funktioniert</p>
                </div>
                <select style="padding: 10px 16px; background: #18181b; border: 1px solid #27272a; border-radius: 8px; color: #fafafa;">
                    <option>Letzte 7 Tage</option>
                    <option>Letzte 30 Tage</option>
                    <option>Letzte 90 Tage</option>
                </select>
            </header>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-icon">&#128065;</div>
                    <div class="stat-content">
                        <span class="stat-value">0</span>
                        <span class="stat-label">Impressions</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#128077;</div>
                    <div class="stat-content">
                        <span class="stat-value">0</span>
                        <span class="stat-label">Engagement</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#128172;</div>
                    <div class="stat-content">
                        <span class="stat-value">0</span>
                        <span class="stat-label">Kommentare</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#128257;</div>
                    <div class="stat-content">
                        <span class="stat-value">0</span>
                        <span class="stat-label">Shares</span>
                    </div>
                </div>
            </div>

            <section class="dashboard-section">
                <div class="section-header">
                    <h2>Performance nach Plattform</h2>
                </div>
                <div class="empty-state" style="background: #18181b; border: 1px solid #27272a; border-radius: 16px;">
                    <p>Verbinde Accounts und erstelle Posts um Analytics zu sehen.</p>
                    <a href="/accounts" class="btn-secondary">Accounts verbinden</a>
                </div>
            </section>

            <section class="dashboard-section">
                <div class="section-header">
                    <h2>Top Posts</h2>
                </div>
                <div class="empty-state" style="background: #18181b; border: 1px solid #27272a; border-radius: 16px;">
                    <p>Noch keine Posts vorhanden.</p>
                    <a href="/compose" class="btn-secondary">Ersten Post erstellen</a>
                </div>
            </section>
        </main>
    </div>

    <script>
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
