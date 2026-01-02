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

            <!-- Messages -->
            <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
            <?php endif; ?>
            <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <!-- Connected Accounts -->
            <section class="accounts-section" id="connected-accounts">
                <h2>Verbundene Accounts</h2>
                <div id="accounts-list">
                    <p class="loading-text">Lade Accounts...</p>
                </div>
            </section>

            <!-- Connect New Account -->
            <section class="accounts-section">
                <h2>Account verbinden</h2>
                <div class="connect-grid">
                    <a href="/auth/instagram" class="connect-card">
                        <div class="connect-icon">📷</div>
                        <div class="connect-info">
                            <h3>Instagram</h3>
                            <p>Posts, Stories und Reels</p>
                        </div>
                        <span class="connect-btn">Verbinden</span>
                    </a>

                    <a href="/auth/tiktok" class="connect-card">
                        <div class="connect-icon">🎵</div>
                        <div class="connect-info">
                            <h3>TikTok</h3>
                            <p>Videos posten</p>
                        </div>
                        <span class="connect-btn">Verbinden</span>
                    </a>

                    <a href="/auth/linkedin" class="connect-card">
                        <div class="connect-icon">💼</div>
                        <div class="connect-info">
                            <h3>LinkedIn</h3>
                            <p>Professional Content</p>
                        </div>
                        <span class="connect-btn">Verbinden</span>
                    </a>

                    <a href="/auth/youtube" class="connect-card">
                        <div class="connect-icon">▶️</div>
                        <div class="connect-info">
                            <h3>YouTube</h3>
                            <p>Videos und Shorts</p>
                        </div>
                        <span class="connect-btn">Verbinden</span>
                    </a>

                    <div class="connect-card disabled">
                        <div class="connect-icon">𝕏</div>
                        <div class="connect-info">
                            <h3>X / Twitter</h3>
                            <p>Tweets und Threads</p>
                        </div>
                        <span class="connect-btn">Bald</span>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <style>
        .accounts-section {
            margin-bottom: 40px;
        }
        .accounts-section h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #fff;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }
        .alert-success {
            background: #052e16;
            color: #4ade80;
            border: 1px solid #166534;
        }
        .alert-error {
            background: #450a0a;
            color: #f87171;
            border: 1px solid #991b1b;
        }
        .loading-text {
            color: #71717a;
            font-size: 14px;
        }
        .no-accounts-msg {
            color: #71717a;
            font-size: 14px;
            padding: 24px;
            background: #18181b;
            border-radius: 12px;
            text-align: center;
        }
        .connected-account {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            background: #18181b;
            border: 1px solid #27272a;
            border-radius: 12px;
            margin-bottom: 12px;
        }
        .connected-account .account-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #27272a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .connected-account .account-info {
            flex: 1;
        }
        .connected-account .account-name {
            font-weight: 600;
            color: #fff;
        }
        .connected-account .account-username {
            font-size: 13px;
            color: #a1a1aa;
        }
        .connected-account .account-platform {
            font-size: 12px;
            color: #71717a;
            text-transform: uppercase;
        }
        .disconnect-btn {
            padding: 8px 16px;
            font-size: 13px;
            color: #f87171;
            background: transparent;
            border: 1px solid #991b1b;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .disconnect-btn:hover {
            background: #450a0a;
        }
        .connect-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }
        .connect-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: #18181b;
            border: 1px solid #27272a;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .connect-card:not(.disabled):hover {
            border-color: #3f3f46;
            background: #1f1f23;
        }
        .connect-card.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .connect-card .connect-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #27272a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .connect-card .connect-info {
            flex: 1;
        }
        .connect-card .connect-info h3 {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 4px;
        }
        .connect-card .connect-info p {
            font-size: 13px;
            color: #71717a;
        }
        .connect-card .connect-btn {
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #fff;
            background: #27272a;
            border-radius: 6px;
        }
        .connect-card:not(.disabled):hover .connect-btn {
            background: #3f3f46;
        }
    </style>

    <script>
        async function loadAccounts() {
            try {
                const response = await fetch('/api/accounts');
                const data = await response.json();
                renderAccounts(data.accounts || []);
            } catch (err) {
                console.error('Failed to load accounts:', err);
                document.getElementById('accounts-list').innerHTML = `
                    <p class="no-accounts-msg">Fehler beim Laden der Accounts</p>
                `;
            }
        }

        function renderAccounts(accounts) {
            const container = document.getElementById('accounts-list');

            if (accounts.length === 0) {
                container.innerHTML = `
                    <p class="no-accounts-msg">Noch keine Accounts verbunden. Verbinde deinen ersten Account!</p>
                `;
                return;
            }

            container.innerHTML = accounts.map(account => `
                <div class="connected-account">
                    <div class="account-icon">${getPlatformIcon(account.platform)}</div>
                    <div class="account-info">
                        <div class="account-name">${account.display_name || account.platform_username}</div>
                        <div class="account-username">@${account.platform_username}</div>
                        <div class="account-platform">${getPlatformName(account.platform)}</div>
                    </div>
                    <button class="disconnect-btn" onclick="disconnectAccount(${account.id})">Trennen</button>
                </div>
            `).join('');
        }

        function getPlatformIcon(platform) {
            const icons = {
                twitter: '𝕏',
                instagram: '📷',
                tiktok: '🎵',
                linkedin: '💼',
                youtube: '▶️'
            };
            return icons[platform] || '📱';
        }

        function getPlatformName(platform) {
            const names = {
                twitter: 'X / Twitter',
                instagram: 'Instagram',
                tiktok: 'TikTok',
                linkedin: 'LinkedIn',
                youtube: 'YouTube'
            };
            return names[platform] || platform;
        }

        async function disconnectAccount(id) {
            if (!confirm('Account wirklich trennen?')) return;

            try {
                const response = await fetch(`/api/accounts/${id}/disconnect`, {
                    method: 'POST'
                });

                if (response.ok) {
                    loadAccounts();
                } else {
                    alert('Fehler beim Trennen des Accounts');
                }
            } catch (err) {
                alert('Verbindungsfehler');
            }
        }

        // Initialize
        loadAccounts();

        // Clear URL params after showing message
        if (window.location.search) {
            history.replaceState({}, document.title, window.location.pathname);
        }
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
