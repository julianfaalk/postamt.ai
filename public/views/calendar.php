<?php $user = Auth::user(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender – Postamt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <div class="app-layout">
        <?php include __DIR__ . '/_sidebar.php'; ?>

        <main class="main-content">
            <header class="page-header">
                <div>
                    <h1>Kalender</h1>
                    <p>Plane deine Posts im Voraus</p>
                </div>
                <a href="/compose" class="btn-primary">+ Neuer Post</a>
            </header>

            <div class="calendar-container">
                <div class="calendar-header">
                    <h2 id="current-month">Dezember 2024</h2>
                    <div class="calendar-nav">
                        <button onclick="prevMonth()">&larr; Zurueck</button>
                        <button onclick="today()">Heute</button>
                        <button onclick="nextMonth()">Weiter &rarr;</button>
                    </div>
                </div>

                <div class="calendar-grid" id="calendar-grid">
                    <!-- Calendar will be rendered by JS -->
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentDate = new Date();
        let posts = [];

        const months = [
            'Januar', 'Februar', 'Maerz', 'April', 'Mai', 'Juni',
            'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'
        ];

        const days = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

        async function loadPosts() {
            try {
                const response = await fetch('/api/posts');
                const data = await response.json();
                posts = data.posts || [];
                renderCalendar();
            } catch (err) {
                console.error('Failed to load posts:', err);
                renderCalendar();
            }
        }

        function getPostsForDate(year, month, day) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            return posts.filter(post => {
                const postDate = (post.scheduled_at || post.created_at || '').substring(0, 10);
                return postDate === dateStr;
            });
        }

        function renderCalendar() {
            const grid = document.getElementById('calendar-grid');
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            document.getElementById('current-month').textContent =
                months[month] + ' ' + year;

            // Clear grid
            grid.innerHTML = '';

            // Add day headers
            days.forEach(day => {
                grid.innerHTML += '<div class="calendar-day-header">' + day + '</div>';
            });

            // First day of month (0 = Sunday, we want Monday = 0)
            const firstDay = new Date(year, month, 1).getDay();
            const startOffset = firstDay === 0 ? 6 : firstDay - 1;

            // Days in month
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Previous month days
            const prevMonthDays = new Date(year, month, 0).getDate();

            // Total cells needed
            const totalCells = Math.ceil((startOffset + daysInMonth) / 7) * 7;

            for (let i = 0; i < totalCells; i++) {
                let dayNumber;
                let classes = 'calendar-day';
                let dayPosts = [];
                let cellYear = year;
                let cellMonth = month;

                if (i < startOffset) {
                    // Previous month
                    dayNumber = prevMonthDays - startOffset + i + 1;
                    classes += ' other-month';
                    cellMonth = month - 1;
                    if (cellMonth < 0) { cellMonth = 11; cellYear--; }
                } else if (i >= startOffset + daysInMonth) {
                    // Next month
                    dayNumber = i - startOffset - daysInMonth + 1;
                    classes += ' other-month';
                    cellMonth = month + 1;
                    if (cellMonth > 11) { cellMonth = 0; cellYear++; }
                } else {
                    // Current month
                    dayNumber = i - startOffset + 1;

                    // Check if today
                    const today = new Date();
                    if (year === today.getFullYear() &&
                        month === today.getMonth() &&
                        dayNumber === today.getDate()) {
                        classes += ' today';
                    }
                }

                dayPosts = getPostsForDate(cellYear, cellMonth, dayNumber);

                let postsHtml = '';
                if (dayPosts.length > 0) {
                    postsHtml = dayPosts.slice(0, 3).map(post => `
                        <div class="calendar-post status-${post.status}" title="${escapeHtml(post.content)}">
                            ${escapeHtml(post.content.substring(0, 20))}${post.content.length > 20 ? '...' : ''}
                        </div>
                    `).join('');
                    if (dayPosts.length > 3) {
                        postsHtml += `<div class="calendar-more">+${dayPosts.length - 3} mehr</div>`;
                    }
                }

                grid.innerHTML += `<div class="${classes}">
                    <div class="calendar-date">${dayNumber}</div>
                    <div class="calendar-posts">${postsHtml}</div>
                </div>`;
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }

        function today() {
            currentDate = new Date();
            renderCalendar();
        }

        async function logout() {
            await fetch('/api/auth/logout', { method: 'POST' });
            window.location.href = '/';
        }

        loadPosts();
    </script>

    <style>
        .calendar-posts {
            margin-top: 4px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .calendar-post {
            font-size: 11px;
            padding: 2px 4px;
            border-radius: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }
        .calendar-post.status-draft { background: #27272a; color: #a1a1aa; }
        .calendar-post.status-scheduled { background: #172554; color: #60a5fa; }
        .calendar-post.status-queued { background: #422006; color: #fbbf24; }
        .calendar-post.status-publishing { background: #422006; color: #fbbf24; }
        .calendar-post.status-published { background: #052e16; color: #4ade80; }
        .calendar-post.status-failed { background: #450a0a; color: #f87171; }
        .calendar-more {
            font-size: 10px;
            color: #71717a;
            padding: 2px 4px;
        }
        .calendar-day {
            min-height: 100px;
        }
    </style>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
