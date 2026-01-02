<?php $user = Auth::user(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Postamt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <div class="app-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="/dashboard" class="logo">Postamt</a>
            </div>

            <nav class="sidebar-nav">
                <a href="/dashboard" class="nav-item active">
                    <span class="nav-icon">&#127968;</span>
                    Dashboard
                </a>
                <a href="/compose" class="nav-item">
                    <span class="nav-icon">&#9998;</span>
                    Neuer Post
                </a>
                <a href="/calendar" class="nav-item">
                    <span class="nav-icon">&#128197;</span>
                    Kalender
                </a>
                <a href="/accounts" class="nav-item">
                    <span class="nav-icon">&#128101;</span>
                    Accounts
                </a>
                <a href="/analytics" class="nav-item">
                    <span class="nav-icon">&#128200;</span>
                    Analytics
                </a>
                <a href="/templates" class="nav-item">
                    <span class="nav-icon">&#128196;</span>
                    Templates
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="/settings" class="nav-item">
                    <span class="nav-icon">&#9881;</span>
                    Einstellungen
                </a>
                <button class="nav-item" onclick="logout()">
                    <span class="nav-icon">&#128682;</span>
                    Abmelden
                </button>
            </div>
        </aside>

        <main class="main-content">
            <header class="page-header">
                <div>
                    <h1>Hallo, <?= htmlspecialchars($user['name'] ?: 'Creator') ?>!</h1>
                    <p>Hier ist dein Ueberblick</p>
                </div>
                <a href="/compose" class="btn-primary">+ Neuer Post</a>
            </header>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-icon">&#128196;</div>
                    <div class="stat-content">
                        <span class="stat-value" id="stat-posts">0</span>
                        <span class="stat-label">Posts gesamt</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#128197;</div>
                    <div class="stat-content">
                        <span class="stat-value" id="stat-scheduled">0</span>
                        <span class="stat-label">Geplant</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#128101;</div>
                    <div class="stat-content">
                        <span class="stat-value" id="stat-accounts">0</span>
                        <span class="stat-label">Accounts</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#128200;</div>
                    <div class="stat-content">
                        <span class="stat-value" id="stat-views">0</span>
                        <span class="stat-label">Views (7 Tage)</span>
                    </div>
                </div>
            </div>

            <section class="dashboard-section">
                <div class="section-header">
                    <h2>Schnellstart</h2>
                </div>
                <div class="quick-actions">
                    <a href="/compose" class="action-card">
                        <span class="action-icon">&#9998;</span>
                        <span class="action-title">Post erstellen</span>
                        <span class="action-desc">Schreib und poste auf allen Plattformen</span>
                    </a>
                    <a href="/accounts" class="action-card">
                        <span class="action-icon">&#128279;</span>
                        <span class="action-title">Account verbinden</span>
                        <span class="action-desc">Fuege deine Social Media Accounts hinzu</span>
                    </a>
                    <a href="/templates" class="action-card">
                        <span class="action-icon">&#128196;</span>
                        <span class="action-title">Template erstellen</span>
                        <span class="action-desc">Spare Zeit mit wiederverwendbaren Vorlagen</span>
                    </a>
                </div>
            </section>

            <section class="dashboard-section">
                <div class="section-header">
                    <h2>Letzte Posts</h2>
                    <a href="/calendar" class="link">Alle ansehen</a>
                </div>
                <div class="posts-list" id="recent-posts">
                    <div class="empty-state">
                        <p>Noch keine Posts vorhanden.</p>
                        <a href="/compose" class="btn-secondary">Ersten Post erstellen</a>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        async function logout() {
            await fetch('/api/auth/logout', { method: 'POST' });
            window.location.href = '/';
        }

        // Load dashboard data
        async function loadDashboardData() {
            // This would load real data from the API
            // For now, just placeholders
        }

        loadDashboardData();
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
