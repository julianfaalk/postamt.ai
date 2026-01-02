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
                                <textarea id="content" name="content" placeholder="Was moechtest du teilen?" oninput="updateCharCount()"></textarea>
                                <div class="character-count" id="char-count">0 / 280</div>
                            </div>

                            <div class="form-group">
                                <label>Plattformen</label>
                                <div class="platform-select" id="platform-select">
                                    <p style="color: #71717a; font-size: 14px;">
                                        Verbinde zuerst deine Social Media Accounts unter
                                        <a href="/accounts">Accounts</a>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="schedule">Zeitplanung</label>
                                <select id="schedule" name="schedule">
                                    <option value="now">Sofort posten</option>
                                    <option value="schedule">Planen fuer...</option>
                                </select>
                            </div>

                            <div class="form-group" id="schedule-time-group" style="display: none;">
                                <label for="scheduled_at">Datum & Uhrzeit</label>
                                <input type="datetime-local" id="scheduled_at" name="scheduled_at">
                            </div>

                            <div style="display: flex; gap: 12px; margin-top: 24px;">
                                <button type="button" class="btn-secondary" onclick="saveDraft()">Als Entwurf speichern</button>
                                <button type="submit" class="btn-primary">Posten</button>
                            </div>
                        </form>
                    </div>

                    <div class="compose-sidebar">
                        <div class="sidebar-card">
                            <h3>Vorschau</h3>
                            <div id="preview-container">
                                <p style="color: #52525b; font-size: 13px;">
                                    Starte mit dem Schreiben um eine Vorschau zu sehen.
                                </p>
                            </div>
                        </div>

                        <div class="sidebar-card">
                            <h3>Tipps</h3>
                            <ul style="font-size: 13px; color: #a1a1aa; padding-left: 16px;">
                                <li style="margin-bottom: 8px;">Beginne mit einem starken Hook</li>
                                <li style="margin-bottom: 8px;">Halte es kurz und praegnant</li>
                                <li style="margin-bottom: 8px;">Nutze relevante Hashtags</li>
                                <li>Fuege einen Call-to-Action hinzu</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function updateCharCount() {
            const content = document.getElementById('content').value;
            const count = content.length;
            const el = document.getElementById('char-count');

            el.textContent = count + ' / 280';
            el.className = 'character-count';

            if (count > 280) {
                el.classList.add('error');
            } else if (count > 240) {
                el.classList.add('warning');
            }
        }

        document.getElementById('schedule').addEventListener('change', function() {
            const group = document.getElementById('schedule-time-group');
            group.style.display = this.value === 'schedule' ? 'block' : 'none';
        });

        function saveDraft() {
            alert('Entwurf gespeichert!');
        }

        document.getElementById('compose-form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Post-Funktion kommt bald!');
        });

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
