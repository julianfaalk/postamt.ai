<?php $user = Auth::user(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Templates – Postamt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <div class="app-layout">
        <?php include __DIR__ . '/_sidebar.php'; ?>

        <main class="main-content">
            <header class="page-header">
                <div>
                    <h1>Templates</h1>
                    <p>Wiederverwendbare Post-Vorlagen</p>
                </div>
                <button class="btn-primary" onclick="showCreateModal()">+ Neues Template</button>
            </header>

            <div id="templates-container">
                <div class="empty-state" style="background: #18181b; border: 1px solid #27272a; border-radius: 16px;">
                    <p>Noch keine Templates vorhanden.</p>
                    <button class="btn-secondary" onclick="showCreateModal()">Erstes Template erstellen</button>
                </div>
            </div>

            <section class="dashboard-section" style="margin-top: 40px;">
                <div class="section-header">
                    <h2>Beispiel-Templates</h2>
                </div>
                <div class="quick-actions">
                    <div class="action-card" style="cursor: pointer;" onclick="useTemplate('hook')">
                        <span class="action-icon">&#128161;</span>
                        <span class="action-title">Hook Template</span>
                        <span class="action-desc">[Hook] + [Story] + [CTA]</span>
                    </div>
                    <div class="action-card" style="cursor: pointer;" onclick="useTemplate('thread')">
                        <span class="action-icon">&#128172;</span>
                        <span class="action-title">Thread Template</span>
                        <span class="action-desc">Nummerierte Liste mit Intro</span>
                    </div>
                    <div class="action-card" style="cursor: pointer;" onclick="useTemplate('tip')">
                        <span class="action-icon">&#128218;</span>
                        <span class="action-title">Tipp des Tages</span>
                        <span class="action-desc">Quick Win fuer deine Audience</span>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function showCreateModal() {
            alert('Template-Editor kommt bald!');
        }

        function useTemplate(type) {
            const templates = {
                hook: 'Hier ist warum [Thema] wichtiger ist als du denkst:\n\n[Deine Story/Erfahrung]\n\n[3 Key Points]\n\nWas denkst du?',
                thread: '1/ [Hook - Das wichtigste zuerst]\n\n2/ [Kontext geben]\n\n3/ [Hauptpunkt 1]\n\n4/ [Hauptpunkt 2]\n\n5/ [Zusammenfassung + CTA]',
                tip: 'Quick Tip: [Thema]\n\n[Erklaerung in 2-3 Saetzen]\n\nSpeicher dir das fuer spaeter!'
            };

            if (templates[type]) {
                // In a real app, this would open the composer with the template
                alert('Template:\n\n' + templates[type]);
            }
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
