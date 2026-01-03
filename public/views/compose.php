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
                                <div class="textarea-footer">
                                    <div class="character-count" id="char-count">0 / 280</div>
                                    <div class="ai-tools">
                                        <button type="button" class="ai-button" id="ai-btn" onclick="toggleAiMenu()">
                                            <span class="ai-sparkle">✨</span>
                                            <span>Mit KI optimieren</span>
                                            <svg class="ai-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><polyline points="6 9 12 15 18 9"/></svg>
                                        </button>
                                        <div class="ai-menu" id="ai-menu">
                                            <button type="button" onclick="optimizeWithAI('improve')">
                                                <span class="ai-menu-icon">🚀</span>
                                                <div>
                                                    <strong>Verbessern</strong>
                                                    <small>Ansprechender & wirkungsvoller</small>
                                                </div>
                                            </button>
                                            <button type="button" onclick="optimizeWithAI('hooks')">
                                                <span class="ai-menu-icon">🎣</span>
                                                <div>
                                                    <strong>Hook hinzufügen</strong>
                                                    <small>Starker Einstieg der fesselt</small>
                                                </div>
                                            </button>
                                            <button type="button" onclick="optimizeWithAI('shorter')">
                                                <span class="ai-menu-icon">✂️</span>
                                                <div>
                                                    <strong>Kürzen</strong>
                                                    <small>Auf das Wesentliche</small>
                                                </div>
                                            </button>
                                            <button type="button" onclick="optimizeWithAI('longer')">
                                                <span class="ai-menu-icon">📝</span>
                                                <div>
                                                    <strong>Erweitern</strong>
                                                    <small>Mehr Details & Kontext</small>
                                                </div>
                                            </button>
                                            <button type="button" onclick="optimizeWithAI('professional')">
                                                <span class="ai-menu-icon">💼</span>
                                                <div>
                                                    <strong>Professioneller</strong>
                                                    <small>Business-tauglich</small>
                                                </div>
                                            </button>
                                            <button type="button" onclick="optimizeWithAI('casual')">
                                                <span class="ai-menu-icon">😎</span>
                                                <div>
                                                    <strong>Lockerer</strong>
                                                    <small>Persönlich & nahbar</small>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Medien</label>
                                <div class="media-upload" id="media-upload">
                                    <input type="file" id="media-input" accept="image/*,video/*" multiple style="display: none;">
                                    <button type="button" class="media-upload-btn" onclick="document.getElementById('media-input').click()">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        Bild/Video hinzufuegen
                                    </button>
                                    <div class="media-preview" id="media-preview"></div>
                                </div>
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
                        <div class="sidebar-card preview-card">
                            <div class="preview-tabs" id="preview-tabs">
                                <!-- Tabs will be rendered by JS based on selected platforms -->
                            </div>
                            <div id="preview-container">
                                <p class="preview-placeholder">
                                    Waehle Plattformen und schreibe Text um eine Vorschau zu sehen.
                                </p>
                            </div>
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
            color: #fff;
        }
        .media-upload-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: #27272a;
            border: 2px dashed #3f3f46;
            border-radius: 8px;
            color: #a1a1aa;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            justify-content: center;
        }
        .media-upload-btn:hover {
            background: #3f3f46;
            border-color: #52525b;
            color: #fff;
        }
        .media-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }
        .media-item {
            position: relative;
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            background: #27272a;
        }
        .media-item img, .media-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .media-item .remove-media {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 20px;
            height: 20px;
            background: rgba(0,0,0,0.7);
            border: none;
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
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

        /* Preview Tabs */
        .preview-card {
            padding: 0 !important;
            overflow: hidden;
        }
        .preview-tabs {
            display: flex;
            border-bottom: 1px solid #27272a;
            overflow-x: auto;
        }
        .preview-tab {
            padding: 12px 16px;
            background: none;
            border: none;
            color: #71717a;
            cursor: pointer;
            font-size: 13px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
        }
        .preview-tab:hover {
            color: #a1a1aa;
        }
        .preview-tab.active {
            color: #fff;
            border-bottom-color: #fff;
        }
        .preview-tab svg {
            width: 16px;
            height: 16px;
        }
        #preview-container {
            padding: 16px;
        }

        /* AI Button Styles */
        .textarea-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }
        .ai-tools {
            position: relative;
        }
        .ai-button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 200% 200%;
            animation: aiGradient 3s ease infinite;
            color: #fff;
            box-shadow: 0 2px 12px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        .ai-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.6);
        }
        .ai-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        .ai-sparkle {
            font-size: 16px;
            animation: sparkle 2s ease-in-out infinite;
        }
        .ai-chevron {
            transition: transform 0.2s;
        }
        .ai-button.active .ai-chevron {
            transform: rotate(180deg);
        }
        @keyframes aiGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes sparkle {
            0%, 100% { transform: scale(1) rotate(0deg); }
            25% { transform: scale(1.2) rotate(-10deg); }
            75% { transform: scale(1.1) rotate(10deg); }
        }
        .ai-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 280px;
            background: #1f1f23;
            border: 1px solid #333;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            z-index: 100;
            overflow: hidden;
        }
        .ai-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .ai-menu button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            background: none;
            border: none;
            color: #fff;
            text-align: left;
            cursor: pointer;
            transition: background 0.15s;
        }
        .ai-menu button:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
        .ai-menu button:not(:last-child) {
            border-bottom: 1px solid #2a2a2e;
        }
        .ai-menu-icon {
            font-size: 20px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #27272a;
            border-radius: 8px;
        }
        .ai-menu button div {
            flex: 1;
        }
        .ai-menu button strong {
            display: block;
            font-size: 14px;
            font-weight: 500;
        }
        .ai-menu button small {
            display: block;
            font-size: 12px;
            color: #71717a;
            margin-top: 2px;
        }
        .ai-loading {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .ai-loading .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Platform-specific post mockups */
        .post-mockup {
            background: #000;
            border-radius: 8px;
            overflow: hidden;
        }

        /* LinkedIn Mockup */
        .linkedin-mockup {
            background: #1b1f23;
        }
        .linkedin-mockup .post-header {
            display: flex;
            gap: 12px;
            padding: 12px;
        }
        .linkedin-mockup .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #0A66C2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
        }
        .linkedin-mockup .author-info .name {
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }
        .linkedin-mockup .author-info .meta {
            color: #71717a;
            font-size: 12px;
        }
        .linkedin-mockup .post-text {
            padding: 0 12px 12px;
            color: #fff;
            font-size: 14px;
            line-height: 1.5;
            white-space: pre-wrap;
            display: block;
            -webkit-line-clamp: unset;
            overflow: visible;
        }
        .linkedin-mockup .post-media {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
        }
        .linkedin-mockup .post-actions {
            display: flex;
            gap: 4px;
            padding: 8px 12px;
            border-top: 1px solid #38434f;
        }
        .linkedin-mockup .action-btn {
            flex: 1;
            padding: 8px;
            color: #71717a;
            font-size: 12px;
            text-align: center;
        }

        /* Twitter/X Mockup */
        .twitter-mockup {
            background: #000;
            border: 1px solid #2f3336;
        }
        .twitter-mockup .post-header {
            display: flex;
            gap: 12px;
            padding: 12px;
        }
        .twitter-mockup .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1d9bf0;
        }
        .twitter-mockup .author-info {
            display: flex;
            gap: 4px;
            align-items: center;
        }
        .twitter-mockup .author-info .name {
            color: #fff;
            font-weight: 700;
            font-size: 15px;
        }
        .twitter-mockup .author-info .handle {
            color: #71767b;
            font-size: 15px;
        }
        .twitter-mockup .post-text {
            padding: 0 12px 12px;
            color: #e7e9ea;
            font-size: 15px;
            line-height: 1.4;
            white-space: pre-wrap;
            display: block;
            -webkit-line-clamp: unset;
            overflow: visible;
        }
        .twitter-mockup .post-media {
            width: 100%;
            max-height: 280px;
            object-fit: cover;
            border-radius: 16px;
            margin: 0 12px 12px;
            width: calc(100% - 24px);
        }
        .twitter-mockup .post-actions {
            display: flex;
            justify-content: space-around;
            padding: 4px 12px 12px;
        }
        .twitter-mockup .action-btn {
            color: #71767b;
            font-size: 13px;
        }

        /* Instagram Mockup */
        .instagram-mockup {
            background: #000;
            border: 1px solid #262626;
        }
        .instagram-mockup .post-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px;
        }
        .instagram-mockup .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        }
        .instagram-mockup .author-info .name {
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }
        .instagram-mockup .post-media {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            background: #262626;
        }
        .instagram-mockup .post-media.placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #71717a;
            font-size: 14px;
        }
        .instagram-mockup .post-actions {
            display: flex;
            gap: 16px;
            padding: 14px;
        }
        .instagram-mockup .action-btn svg {
            width: 24px;
            height: 24px;
            color: #fff;
        }
        .instagram-mockup .post-text {
            padding: 0 14px 14px;
            color: #fff;
            font-size: 14px;
            line-height: 1.4;
            display: block;
            -webkit-line-clamp: unset;
            overflow: visible;
            white-space: pre-wrap;
        }
        .instagram-mockup .post-text .username {
            font-weight: 600;
            margin-right: 6px;
        }
    </style>

    <script>
        let accounts = [];
        let mediaFiles = [];

        // Load connected accounts
        async function loadAccounts() {
            try {
                const response = await fetch('/api/accounts');
                const data = await response.json();
                accounts = data.accounts || [];
                renderAccountSelect();
                updatePreview(); // Show preview immediately after accounts load
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
                        <div class="platform-username">${account.platform_username}</div>
                    </div>
                </label>
            `).join('');
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

        let activePreviewPlatform = null;

        function updatePreview() {
            const content = document.getElementById('content').value;
            const count = content.length;
            const charEl = document.getElementById('char-count');

            charEl.textContent = count + ' / 280';
            charEl.className = 'character-count';
            if (count > 280) {
                charEl.classList.add('error');
            } else if (count > 240) {
                charEl.classList.add('warning');
            }

            renderPreviewTabs();
            renderPlatformPreview();
        }

        function renderPreviewTabs() {
            const tabsEl = document.getElementById('preview-tabs');
            const selectedPlatforms = getSelectedPlatforms();

            if (selectedPlatforms.length === 0) {
                tabsEl.innerHTML = '';
                activePreviewPlatform = null;
                return;
            }

            if (!activePreviewPlatform || !selectedPlatforms.find(p => p.platform === activePreviewPlatform)) {
                activePreviewPlatform = selectedPlatforms[0].platform;
            }

            tabsEl.innerHTML = selectedPlatforms.map(p => `
                <button type="button" class="preview-tab ${p.platform === activePreviewPlatform ? 'active' : ''}"
                        onclick="setActivePreview('${p.platform}')">
                    ${getSmallPlatformIcon(p.platform)}
                    ${getPlatformName(p.platform)}
                </button>
            `).join('');
        }

        function setActivePreview(platform) {
            activePreviewPlatform = platform;
            renderPreviewTabs();
            renderPlatformPreview();
        }

        function getSelectedPlatforms() {
            const checkboxes = document.querySelectorAll('input[name="platforms"]:checked');
            return Array.from(checkboxes).map(cb => {
                const account = accounts.find(a => a.id == cb.value);
                return account;
            }).filter(Boolean);
        }

        function getMediaPreviewUrl() {
            if (mediaFiles.length > 0) {
                return URL.createObjectURL(mediaFiles[0]);
            }
            return null;
        }

        function renderPlatformPreview() {
            const previewEl = document.getElementById('preview-container');
            const content = document.getElementById('content').value.trim();
            const selectedPlatforms = getSelectedPlatforms();

            if (selectedPlatforms.length === 0) {
                previewEl.innerHTML = '<p class="preview-placeholder">Waehle mindestens eine Plattform aus.</p>';
                return;
            }

            const account = selectedPlatforms.find(p => p.platform === activePreviewPlatform) || selectedPlatforms[0];
            const mediaUrl = getMediaPreviewUrl();
            const isVideo = mediaFiles.length > 0 && mediaFiles[0].type.startsWith('video/');
            const displayContent = content || 'Dein Text erscheint hier...';

            switch (account.platform) {
                case 'linkedin':
                    previewEl.innerHTML = renderLinkedInMockup(displayContent, account, mediaUrl, isVideo, !content);
                    break;
                case 'twitter':
                    previewEl.innerHTML = renderTwitterMockup(displayContent, account, mediaUrl, isVideo, !content);
                    break;
                case 'instagram':
                    previewEl.innerHTML = renderInstagramMockup(displayContent, account, mediaUrl, isVideo, !content);
                    break;
                default:
                    previewEl.innerHTML = `<div class="preview-content">${escapeHtml(displayContent)}</div>`;
            }
        }

        function renderLinkedInMockup(content, account, mediaUrl, isVideo, isPlaceholder = false) {
            const initials = (account.display_name || account.platform_username || 'U').substring(0, 2).toUpperCase();
            const avatarHtml = account.avatar_url
                ? `<img src="${escapeHtml(account.avatar_url)}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`
                : initials;
            const textStyle = isPlaceholder ? 'color: #71717a; font-style: italic;' : '';
            return `
                <div class="post-mockup linkedin-mockup">
                    <div class="post-header">
                        <div class="avatar">${avatarHtml}</div>
                        <div class="author-info">
                            <div class="name">${escapeHtml(account.display_name || account.platform_username)}</div>
                            <div class="meta">Gerade eben</div>
                        </div>
                    </div>
                    <div class="post-text" style="${textStyle}">${escapeHtml(content)}</div>
                    ${mediaUrl ? (isVideo
                        ? `<video class="post-media" src="${mediaUrl}" controls></video>`
                        : `<img class="post-media" src="${mediaUrl}" alt="">`)
                        : ''}
                    <div class="post-actions">
                        <span class="action-btn">Gefaellt mir</span>
                        <span class="action-btn">Kommentieren</span>
                        <span class="action-btn">Teilen</span>
                    </div>
                </div>
            `;
        }

        function renderTwitterMockup(content, account, mediaUrl, isVideo, isPlaceholder = false) {
            const avatarStyle = account.avatar_url
                ? `background-image:url('${escapeHtml(account.avatar_url)}');background-size:cover;background-position:center;`
                : '';
            const textStyle = isPlaceholder ? 'color: #71767b; font-style: italic;' : '';
            return `
                <div class="post-mockup twitter-mockup">
                    <div class="post-header">
                        <div class="avatar" style="${avatarStyle}"></div>
                        <div class="author-info">
                            <span class="name">${escapeHtml(account.display_name || account.platform_username)}</span>
                            <span class="handle">@${escapeHtml(account.platform_username)}</span>
                        </div>
                    </div>
                    <div class="post-text" style="${textStyle}">${escapeHtml(content)}</div>
                    ${mediaUrl ? (isVideo
                        ? `<video class="post-media" src="${mediaUrl}" controls></video>`
                        : `<img class="post-media" src="${mediaUrl}" alt="">`)
                        : ''}
                    <div class="post-actions">
                        <span class="action-btn">Reply</span>
                        <span class="action-btn">Repost</span>
                        <span class="action-btn">Like</span>
                        <span class="action-btn">Share</span>
                    </div>
                </div>
            `;
        }

        function renderInstagramMockup(content, account, mediaUrl, isVideo, isPlaceholder = false) {
            const avatarStyle = account.avatar_url
                ? `background-image:url('${escapeHtml(account.avatar_url)}');background-size:cover;background-position:center;`
                : '';
            const textStyle = isPlaceholder ? 'color: #71717a; font-style: italic;' : '';
            return `
                <div class="post-mockup instagram-mockup">
                    <div class="post-header">
                        <div class="avatar" style="${avatarStyle}"></div>
                        <div class="author-info">
                            <div class="name">${escapeHtml(account.platform_username)}</div>
                        </div>
                    </div>
                    ${mediaUrl
                        ? (isVideo
                            ? `<video class="post-media" src="${mediaUrl}" controls></video>`
                            : `<img class="post-media" src="${mediaUrl}" alt="">`)
                        : `<div class="post-media placeholder">Bild oder Video hinzufuegen</div>`}
                    <div class="post-actions">
                        <span class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span>
                        <span class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></span>
                        <span class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
                    </div>
                    <div class="post-text"><span class="username">${escapeHtml(account.platform_username)}</span><span style="${textStyle}">${escapeHtml(content)}</span></div>
                </div>
            `;
        }

        function getSmallPlatformIcon(platform) {
            const icons = {
                linkedin: '<svg viewBox="0 0 24 24" fill="#0A66C2"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
                twitter: '<svg viewBox="0 0 24 24" fill="#fff"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
                instagram: '<svg viewBox="0 0 24 24" fill="#E4405F"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
                youtube: '<svg viewBox="0 0 24 24" fill="#FF0000"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
                tiktok: '<svg viewBox="0 0 24 24" fill="#fff"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>'
            };
            return icons[platform] || '';
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
                        publish_now: schedule === 'now',
                        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
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

        // Media upload handling
        document.getElementById('media-input').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                if (mediaFiles.length >= 4) {
                    showMessage('Maximal 4 Medien erlaubt', 'error');
                    return;
                }
                mediaFiles.push(file);
            });
            renderMediaPreview();
            e.target.value = ''; // Reset input
        });

        function renderMediaPreview() {
            const container = document.getElementById('media-preview');
            container.innerHTML = mediaFiles.map((file, index) => {
                const url = URL.createObjectURL(file);
                const isVideo = file.type.startsWith('video/');
                return `
                    <div class="media-item">
                        ${isVideo
                            ? `<video src="${url}" muted></video>`
                            : `<img src="${url}" alt="Preview">`}
                        <button type="button" class="remove-media" onclick="removeMedia(${index})">×</button>
                    </div>
                `;
            }).join('');
            updatePreview(); // Update platform preview when media changes
        }

        function removeMedia(index) {
            mediaFiles.splice(index, 1);
            renderMediaPreview();
        }

        // Update preview when platform selection changes
        document.addEventListener('change', function(e) {
            if (e.target.name === 'platforms') {
                updatePreview();
            }
        });

        // AI Menu Functions
        function toggleAiMenu() {
            const menu = document.getElementById('ai-menu');
            const btn = document.getElementById('ai-btn');
            menu.classList.toggle('show');
            btn.classList.toggle('active');
        }

        // Close AI menu when clicking outside
        document.addEventListener('click', function(e) {
            const aiTools = document.querySelector('.ai-tools');
            if (aiTools && !aiTools.contains(e.target)) {
                document.getElementById('ai-menu').classList.remove('show');
                document.getElementById('ai-btn').classList.remove('active');
            }
        });

        async function optimizeWithAI(action) {
            const content = document.getElementById('content').value.trim();
            const btn = document.getElementById('ai-btn');
            const menu = document.getElementById('ai-menu');

            if (!content) {
                showMessage('Bitte schreibe erst einen Text', 'error');
                menu.classList.remove('show');
                btn.classList.remove('active');
                return;
            }

            // Get selected platform for context
            const selectedPlatforms = getSelectedPlatforms();
            const platform = selectedPlatforms.length > 0 ? selectedPlatforms[0].platform : 'general';

            // Close menu and show loading state
            menu.classList.remove('show');
            btn.classList.remove('active');
            btn.disabled = true;
            btn.innerHTML = `<span class="ai-loading"><span class="spinner"></span><span>KI denkt nach...</span></span>`;

            try {
                const response = await fetch('/api/ai/optimize', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        text: content,
                        platform: platform,
                        action: action
                    })
                });

                const data = await response.json();

                if (response.ok && data.text) {
                    document.getElementById('content').value = data.text;
                    updatePreview();
                    showMessage('Text wurde optimiert!', 'success');
                } else {
                    showMessage(data.error || 'Optimierung fehlgeschlagen', 'error');
                }
            } catch (err) {
                console.error('AI optimization error:', err);
                showMessage('Verbindungsfehler', 'error');
            }

            // Reset button
            btn.disabled = false;
            btn.innerHTML = `
                <span class="ai-sparkle">✨</span>
                <span>Mit KI optimieren</span>
                <svg class="ai-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><polyline points="6 9 12 15 18 9"/></svg>
            `;
        }

        // Initialize
        loadAccounts();
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
