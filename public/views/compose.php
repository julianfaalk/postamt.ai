<?php $user = Auth::user(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neuer Post – Postamt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <div class="app-layout">
        <?php include __DIR__ . '/_sidebar.php'; ?>

        <main class="main-content">
            <header class="page-header">
                <div>
                    <h1>Neuer Post</h1>
                    <p>Erstelle Content fuer alle Plattformen</p>
                </div>
            </header>

            <div class="compose-container">
                <div class="compose-main">
                    <div class="compose-editor">
                        <form id="compose-form">
                            <div class="form-group">
                                <label for="content">Dein Post</label>
                                <textarea id="content" name="content" placeholder="Was moechtest du teilen?" oninput="updatePreview()"></textarea>
                                <div class="character-count" id="char-count">0 / 280</div>
                            </div>

                            <div class="form-group">
                                <label>Plattformen</label>
                                <div class="platform-select" id="platform-select">
                                    <p class="loading-text">Lade Accounts...</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="schedule">Zeitplanung</label>
                                <select id="schedule" name="schedule">
                                    <option value="now">Sofort posten</option>
                                    <option value="schedule">Planen fuer...</option>
                                    <option value="draft">Als Entwurf speichern</option>
                                </select>
                            </div>

                            <div class="form-group" id="schedule-time-group" style="display: none;">
                                <label for="scheduled_at">Datum & Uhrzeit</label>
                                <input type="datetime-local" id="scheduled_at" name="scheduled_at">
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-primary" id="submit-btn">Posten</button>
                            </div>

                            <div class="message-box" id="message-box" style="display: none;"></div>
                        </form>
                    </div>

                    <div class="compose-sidebar">
                        <div class="sidebar-card">
                            <h3>Vorschau</h3>
                            <div id="preview-container">
                                <p class="preview-placeholder">
                                    Starte mit dem Schreiben um eine Vorschau zu sehen.
                                </p>
                            </div>
                        </div>

                        <div class="sidebar-card">
                            <h3>Tipps</h3>
                            <ul class="tips-list">
                                <li>Beginne mit einem starken Hook</li>
                                <li>Halte es kurz und praegnant</li>
                                <li>Nutze relevante Hashtags</li>
                                <li>Fuege einen Call-to-Action hinzu</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        .platform-checkbox {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: #27272a;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .platform-checkbox:hover {
            background: #3f3f46;
        }
        .platform-checkbox input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .platform-checkbox .platform-info {
            flex: 1;
        }
        .platform-checkbox .platform-name {
            font-weight: 500;
            color: #fff;
        }
        .platform-checkbox .platform-username {
            font-size: 13px;
            color: #a1a1aa;
        }
        .platform-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #3f3f46;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .no-accounts {
            padding: 20px;
            background: #27272a;
            border-radius: 8px;
            text-align: center;
        }
        .no-accounts p {
            color: #a1a1aa;
            margin-bottom: 12px;
        }
        .no-accounts a {
            color: #fff;
            text-decoration: underline;
        }
        .message-box {
            margin-top: 16px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
        }
        .message-box.success {
            background: #052e16;
            color: #4ade80;
            border: 1px solid #166534;
        }
        .message-box.error {
            background: #450a0a;
            color: #f87171;
            border: 1px solid #991b1b;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        .loading-text {
            color: #71717a;
            font-size: 14px;
        }
        .preview-placeholder {
            color: #52525b;
            font-size: 13px;
        }
        .tips-list {
            font-size: 13px;
            color: #a1a1aa;
            padding-left: 16px;
        }
        .tips-list li {
            margin-bottom: 8px;
        }
        #preview-container .preview-content {
            background: #27272a;
            padding: 16px;
            border-radius: 8px;
            white-space: pre-wrap;
            word-break: break-word;
        }
    </style>

    <script>
        let accounts = [];

        // Load connected accounts
        async function loadAccounts() {
            try {
                const response = await fetch('/api/accounts');
                const data = await response.json();
                accounts = data.accounts || [];
                renderAccountSelect();
            } catch (err) {
                console.error('Failed to load accounts:', err);
                document.getElementById('platform-select').innerHTML = `
                    <div class="no-accounts">
                        <p>Fehler beim Laden der Accounts</p>
                    </div>
                `;
            }
        }

        function renderAccountSelect() {
            const container = document.getElementById('platform-select');

            if (accounts.length === 0) {
                container.innerHTML = `
                    <div class="no-accounts">
                        <p>Keine Social Media Accounts verbunden</p>
                        <a href="/accounts">Jetzt verbinden</a>
                    </div>
                `;
                return;
            }

            container.innerHTML = accounts.map(account => `
                <label class="platform-checkbox">
                    <input type="checkbox" name="platforms" value="${account.id}" checked>
                    <div class="platform-icon">${getPlatformIcon(account.platform)}</div>
                    <div class="platform-info">
                        <div class="platform-name">${getPlatformName(account.platform)}</div>
                        <div class="platform-username">@${account.platform_username}</div>
                    </div>
                </label>
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

        function updatePreview() {
            const content = document.getElementById('content').value;
            const count = content.length;
            const charEl = document.getElementById('char-count');
            const previewEl = document.getElementById('preview-container');

            charEl.textContent = count + ' / 280';
            charEl.className = 'character-count';
            if (count > 280) {
                charEl.classList.add('error');
            } else if (count > 240) {
                charEl.classList.add('warning');
            }

            if (content.trim()) {
                previewEl.innerHTML = `<div class="preview-content">${escapeHtml(content)}</div>`;
            } else {
                previewEl.innerHTML = '<p class="preview-placeholder">Starte mit dem Schreiben um eine Vorschau zu sehen.</p>';
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        document.getElementById('schedule').addEventListener('change', function() {
            const group = document.getElementById('schedule-time-group');
            const btn = document.getElementById('submit-btn');

            if (this.value === 'schedule') {
                group.style.display = 'block';
                btn.textContent = 'Planen';
            } else if (this.value === 'draft') {
                group.style.display = 'none';
                btn.textContent = 'Als Entwurf speichern';
            } else {
                group.style.display = 'none';
                btn.textContent = 'Jetzt posten';
            }
        });

        document.getElementById('compose-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const content = document.getElementById('content').value.trim();
            const schedule = document.getElementById('schedule').value;
            const scheduledAt = document.getElementById('scheduled_at').value;
            const messageBox = document.getElementById('message-box');
            const btn = document.getElementById('submit-btn');

            // Get selected platforms
            const platformCheckboxes = document.querySelectorAll('input[name="platforms"]:checked');
            const platforms = Array.from(platformCheckboxes).map(cb => cb.value);

            if (!content) {
                showMessage('Bitte gib einen Text ein', 'error');
                return;
            }

            if (platforms.length === 0 && schedule !== 'draft') {
                showMessage('Bitte waehle mindestens eine Plattform', 'error');
                return;
            }

            if (schedule === 'schedule' && !scheduledAt) {
                showMessage('Bitte waehle ein Datum und Uhrzeit', 'error');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Wird gespeichert...';

            try {
                const response = await fetch('/api/posts', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        content: content,
                        platforms: platforms,
                        scheduled_at: schedule === 'schedule' ? scheduledAt : null,
                        publish_now: schedule === 'now'
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    const statusMsg = {
                        'draft': 'Entwurf gespeichert!',
                        'scheduled': 'Post geplant!',
                        'queued': 'Post wird veroeffentlicht...'
                    };
                    showMessage(statusMsg[data.post.status] || 'Gespeichert!', 'success');

                    // Clear form
                    document.getElementById('content').value = '';
                    updatePreview();

                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1500);
                } else {
                    showMessage(data.error || 'Fehler beim Speichern', 'error');
                }
            } catch (err) {
                showMessage('Verbindungsfehler', 'error');
            }

            btn.disabled = false;
            document.getElementById('schedule').dispatchEvent(new Event('change'));
        });

        function showMessage(text, type) {
            const box = document.getElementById('message-box');
            box.textContent = text;
            box.className = 'message-box ' + type;
            box.style.display = 'block';
        }

        // Initialize
        loadAccounts();
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
