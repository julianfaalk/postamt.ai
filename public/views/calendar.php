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

        const months = [
            'Januar', 'Februar', 'Maerz', 'April', 'Mai', 'Juni',
            'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'
        ];

        const days = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

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

                if (i < startOffset) {
                    // Previous month
                    dayNumber = prevMonthDays - startOffset + i + 1;
                    classes += ' other-month';
                } else if (i >= startOffset + daysInMonth) {
                    // Next month
                    dayNumber = i - startOffset - daysInMonth + 1;
                    classes += ' other-month';
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

                grid.innerHTML += '<div class="' + classes + '">' +
                    '<div class="calendar-date">' + dayNumber + '</div>' +
                    '</div>';
            }
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

        renderCalendar();
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
