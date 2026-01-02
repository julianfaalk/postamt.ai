<?php $user = Auth::user(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post bearbeiten – Postamt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <div class="app-layout">
        <?php include __DIR__ . '/_sidebar.php'; ?>

        <main class="main-content">
            <header class="page-header">
                <div>
                    <a href="/calendar" class="back-link">&larr; Zurueck zum Kalender</a>
                    <h1 id="page-title">Post laden...</h1>
                </div>
                <div class="header-actions" id="header-actions"></div>
            </header>

            <div class="post-container" id="post-container">
                <div class="loading">Lade Post...</div>
            </div>
        </main>
    </div>

    <style>
        .back-link {
            color: #a1a1aa;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .back-link:hover {
            color: #fff;
        }
        .post-container {
            max-width: 800px;
        }
        .post-card {
            background: #18181b;
            border: 1px solid #27272a;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .post-content {
            font-size: 16px;
            line-height: 1.6;
            color: #fff;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .post-content.editing {
            width: 100%;
            min-height: 150px;
            background: #27272a;
            border: 1px solid #3f3f46;
            border-radius: 8px;
            padding: 12px;
            font-family: inherit;
            font-size: 16px;
            color: #fff;
            resize: vertical;
        }
        .post-meta {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #27272a;
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #a1a1aa;
        }
        .meta-item svg {
            width: 16px;
            height: 16px;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        .status-badge.draft { background: #27272a; color: #a1a1aa; }
        .status-badge.scheduled { background: #172554; color: #60a5fa; }
        .status-badge.queued { background: #422006; color: #fbbf24; }
        .status-badge.publishing { background: #422006; color: #fbbf24; }
        .status-badge.published { background: #052e16; color: #4ade80; }
        .status-badge.failed { background: #450a0a; color: #f87171; }
        .status-badge.partial { background: #431407; color: #fb923c; }
        .platforms-section {
            margin-top: 24px;
        }
        .platforms-section h3 {
            font-size: 14px;
            color: #a1a1aa;
            margin-bottom: 12px;
        }
        .platform-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .platform-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #27272a;
            border-radius: 8px;
        }
        .platform-icon-svg {
            width: 24px;
            height: 24px;
        }
        .platform-icon-svg.linkedin { color: #0A66C2; }
        .platform-icon-svg.twitter { color: #fff; }
        .platform-icon-svg.instagram { color: #E4405F; }
        .platform-icon-svg.youtube { color: #FF0000; }
        .platform-icon-svg.tiktok { color: #fff; }
        .platform-info {
            flex: 1;
        }
        .platform-name {
            font-weight: 500;
            color: #fff;
        }
        .platform-username {
            font-size: 13px;
            color: #71717a;
        }
        .platform-status {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .platform-status.pending { background: #27272a; color: #a1a1aa; }
        .platform-status.published { background: #052e16; color: #4ade80; }
        .platform-status.failed { background: #450a0a; color: #f87171; }
        .schedule-section {
            margin-top: 24px;
            padding: 16px;
            background: #27272a;
            border-radius: 8px;
        }
        .schedule-section label {
            display: block;
            font-size: 14px;
            color: #a1a1aa;
            margin-bottom: 8px;
        }
        .schedule-section input {
            background: #18181b;
            border: 1px solid #3f3f46;
            border-radius: 6px;
            padding: 10px 12px;
            color: #fff;
            font-size: 14px;
        }
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        .btn-danger {
            background: #450a0a;
            color: #f87171;
            border: 1px solid #991b1b;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-danger:hover {
            background: #7f1d1d;
        }
        .btn-secondary {
            background: #27272a;
            color: #fff;
            border: 1px solid #3f3f46;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-secondary:hover {
            background: #3f3f46;
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
        .loading {
            color: #71717a;
            padding: 40px;
            text-align: center;
        }
    </style>

    <script>
        const postId = window.location.pathname.split('/').pop();
        let post = null;
        let isEditing = false;

        async function loadPost() {
            try {
                const response = await fetch(`/api/posts/${postId}`);
                if (!response.ok) {
                    throw new Error('Post nicht gefunden');
                }
                const data = await response.json();
                post = data.post;
                renderPost();
            } catch (err) {
                document.getElementById('post-container').innerHTML = `
                    <div class="post-card">
                        <p style="color: #f87171;">Fehler: ${err.message}</p>
                        <a href="/calendar" class="btn-secondary" style="display: inline-block; margin-top: 16px;">Zurueck zum Kalender</a>
                    </div>
                `;
            }
        }

        function renderPost() {
            const canEdit = ['draft', 'scheduled'].includes(post.status);

            document.getElementById('page-title').textContent = canEdit ? 'Post bearbeiten' : 'Post Details';

            document.getElementById('header-actions').innerHTML = canEdit ? `
                <button class="btn-primary" onclick="toggleEdit()">${isEditing ? 'Abbrechen' : 'Bearbeiten'}</button>
            ` : '';

            const statusLabels = {
                'draft': 'Entwurf',
                'scheduled': 'Geplant',
                'queued': 'In Warteschlange',
                'publishing': 'Wird gepostet...',
                'published': 'Veroeffentlicht',
                'failed': 'Fehlgeschlagen',
                'partial': 'Teilweise'
            };

            document.getElementById('post-container').innerHTML = `
                <div class="post-card">
                    ${isEditing ? `
                        <textarea class="post-content editing" id="edit-content">${escapeHtml(post.content)}</textarea>
                    ` : `
                        <div class="post-content">${escapeHtml(post.content)}</div>
                    `}

                    <div class="post-meta">
                        <div class="meta-item">
                            <span class="status-badge ${post.status}">${statusLabels[post.status] || post.status}</span>
                        </div>
                        <div class="meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            ${formatDate(post.scheduled_at || post.created_at)}
                        </div>
                    </div>

                    ${isEditing && canEdit ? `
                        <div class="schedule-section">
                            <label for="edit-schedule">Geplant fuer</label>
                            <input type="datetime-local" id="edit-schedule" value="${formatDateTimeLocal(post.scheduled_at)}">
                        </div>
                    ` : ''}

                    ${post.platforms && post.platforms.length > 0 ? `
                        <div class="platforms-section">
                            <h3>Plattformen</h3>
                            <div class="platform-list">
                                ${post.platforms.map(p => `
                                    <div class="platform-item">
                                        ${getPlatformIcon(p.platform)}
                                        <div class="platform-info">
                                            <div class="platform-name">${getPlatformName(p.platform)}</div>
                                            <div class="platform-username">${p.platform_username}</div>
                                        </div>
                                        <span class="platform-status ${p.status}">${getPlatformStatusLabel(p.status)}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}

                    ${isEditing ? `
                        <div class="action-buttons">
                            <button class="btn-primary" onclick="savePost()">Speichern</button>
                            <button class="btn-secondary" onclick="toggleEdit()">Abbrechen</button>
                            <button class="btn-danger" onclick="deletePost()">Loeschen</button>
                        </div>
                    ` : canEdit ? `
                        <div class="action-buttons">
                            <button class="btn-danger" onclick="deletePost()">Post loeschen</button>
                        </div>
                    ` : ''}

                    <div class="message-box" id="message-box" style="display: none;"></div>
                </div>
            `;
        }

        function toggleEdit() {
            isEditing = !isEditing;
            renderPost();
        }

        async function savePost() {
            const content = document.getElementById('edit-content').value.trim();
            const scheduledAt = document.getElementById('edit-schedule')?.value;

            if (!content) {
                showMessage('Inhalt darf nicht leer sein', 'error');
                return;
            }

            try {
                const response = await fetch(`/api/posts/${postId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        content: content,
                        scheduled_at: scheduledAt || null,
                        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
                    })
                });

                if (response.ok) {
                    showMessage('Gespeichert!', 'success');
                    isEditing = false;
                    await loadPost();
                } else {
                    const data = await response.json();
                    showMessage(data.error || 'Fehler beim Speichern', 'error');
                }
            } catch (err) {
                showMessage('Verbindungsfehler', 'error');
            }
        }

        async function deletePost() {
            if (!confirm('Post wirklich loeschen?')) return;

            try {
                const response = await fetch(`/api/posts/${postId}/delete`, {
                    method: 'POST'
                });

                if (response.ok) {
                    window.location.href = '/calendar';
                } else {
                    showMessage('Fehler beim Loeschen', 'error');
                }
            } catch (err) {
                showMessage('Verbindungsfehler', 'error');
            }
        }

        function showMessage(text, type) {
            const box = document.getElementById('message-box');
            if (box) {
                box.textContent = text;
                box.className = 'message-box ' + type;
                box.style.display = 'block';
            }
        }

        function formatDate(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            return date.toLocaleString('de-DE', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatDateTimeLocal(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            return date.toISOString().slice(0, 16);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function getPlatformIcon(platform) {
            const icons = {
                linkedin: '<svg class="platform-icon-svg linkedin" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
                twitter: '<svg class="platform-icon-svg twitter" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
                instagram: '<svg class="platform-icon-svg instagram" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
                youtube: '<svg class="platform-icon-svg youtube" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
                tiktok: '<svg class="platform-icon-svg tiktok" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>'
            };
            return icons[platform] || '<svg class="platform-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>';
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

        function getPlatformStatusLabel(status) {
            const labels = {
                'pending': 'Ausstehend',
                'published': 'Veroeffentlicht',
                'failed': 'Fehlgeschlagen'
            };
            return labels[status] || status;
        }

        loadPost();
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
