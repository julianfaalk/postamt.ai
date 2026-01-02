<aside class="sidebar">
    <div class="sidebar-header">
        <a href="/dashboard" class="logo">Postamt</a>
    </div>

    <nav class="sidebar-nav">
        <a href="/dashboard" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') === 0 ? 'active' : '' ?>">
            <span class="nav-icon">&#127968;</span>
            Dashboard
        </a>
        <a href="/compose" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/compose') === 0 ? 'active' : '' ?>">
            <span class="nav-icon">&#9998;</span>
            Neuer Post
        </a>
        <a href="/calendar" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/calendar') === 0 ? 'active' : '' ?>">
            <span class="nav-icon">&#128197;</span>
            Kalender
        </a>
        <a href="/accounts" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/accounts') === 0 ? 'active' : '' ?>">
            <span class="nav-icon">&#128101;</span>
            Accounts
        </a>
        <a href="/analytics" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/analytics') === 0 ? 'active' : '' ?>">
            <span class="nav-icon">&#128200;</span>
            Analytics
        </a>
        <a href="/templates" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/templates') === 0 ? 'active' : '' ?>">
            <span class="nav-icon">&#128196;</span>
            Templates
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="/settings" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/settings') === 0 ? 'active' : '' ?>">
            <span class="nav-icon">&#9881;</span>
            Einstellungen
        </a>
        <button class="nav-item" onclick="logout()">
            <span class="nav-icon">&#128682;</span>
            Abmelden
        </button>
    </div>
</aside>
