# Postamt.ai – Projekt-Spezifikation

## Übersicht

**Postamt** – Ein Social Media Management Tool für Creator und Solopreneure, das Content-Erstellung unterstützt (nicht automatisiert) und Cross-Platform-Posting ermöglicht. Der Fokus liegt auf Authentizität – kein AI-Slop, sondern Tools die dem User helfen, schneller und konsistenter zu posten.

---

## Zielgruppe

- Indie Hacker / Solopreneure
- Content Creator (1K-50K Follower)
- Kleine Teams (2-5 Personen)
- Leute die aktuell 1-2h täglich mit manuellem Cross-Posting verschwenden

---

## Kernprinzipien

1. **Einfachheit** – Kein Feature-Bloat, keine 47 Dashboards
2. **Speed** – 30 Sekunden statt 30 Minuten für einen Post
3. **Authentizität** – AI assistiert, aber der User bleibt Autor
4. **Fair Pricing** – Kein Enterprise-Bullshit, keine versteckten Kosten

---

## Unterstützte Plattformen

| Plattform | Priorität | API-Status |
|-----------|-----------|------------|
| X/Twitter | P0 | Vollständig verfügbar |
| Instagram | P0 | Meta Graph API (Business/Creator Accounts) |
| TikTok | P0 | Content Posting API |
| LinkedIn | P1 | Gute API verfügbar |
| YouTube | P1 | Data API v3 |
| Threads | P2 | Meta Graph API |
| Bluesky | P2 | AT Protocol |

---

## Features

### Phase 1: MVP (Woche 1-4)

#### 1.1 Multi-Platform Posting
- Ein Post erstellen → auf alle verbundenen Plattformen gleichzeitig posten
- Plattform-spezifische Anpassungen (Zeichenlimit, Hashtags, Mentions)
- Media-Upload (Bilder, Videos) mit automatischer Format-Anpassung
- Sofort-Post oder Scheduling

#### 1.2 Account Management
- OAuth-Verbindung zu allen Plattformen
- Mehrere Accounts pro Plattform möglich
- Account-Gruppen (z.B. "Privat", "Business", "Client A")

#### 1.3 Content Calendar
- Kalender-Ansicht aller geplanten Posts
- Drag & Drop zum Umplanen
- Filter nach Plattform/Account

#### 1.4 Basic Analytics
- Post-Performance (Likes, Comments, Shares, Views)
- Beste Posting-Zeiten (basierend auf eigenen Daten)

### Phase 2: Content Tools (Woche 5-8)

#### 2.1 Hook-Vorschläge
- AI-generierte Hook-Varianten basierend auf User-Input
- User wählt aus oder passt an – KEINE automatische Generierung
- Speichern von erfolgreichen Hooks als Templates

#### 2.2 Hashtag-Recherche
- Trending Hashtags pro Plattform
- Hashtag-Gruppen speichern und wiederverwenden
- Performance-Tracking für Hashtags

#### 2.3 Template-System
- Wiederkehrende Post-Strukturen speichern
- Variablen (z.B. {topic}, {cta})
- Schnelles Erstellen ähnlicher Posts

#### 2.4 Media Tools
- Einfacher Image-Editor (Crop, Resize für jede Plattform)
- Thumbnail-Vorschläge für Videos
- Alt-Text Generator (Accessibility)

### Phase 3: Advanced (Woche 9-12)

#### 3.1 Content Queue
- Evergreen-Content der rotiert
- Auto-Repost mit anpassbaren Intervallen

#### 3.2 Team Features
- Invite Team Members
- Approval Workflow
- Kommentare auf Posts

#### 3.3 Advanced Analytics
- Cross-Platform Vergleich
- Content-Typen Analyse (was performt am besten?)
- Export als Report

---

## Tech Stack

### Constraints (vom User vorgegeben)
- **Backend:** PHP (kein Framework, plain PHP)
- **Frontend:** HTML, CSS, JavaScript (vanilla, keine Libraries)
- **Database:** SQLite
- **Deployment:** Docker Container auf Hetzner VPS
- **Orchestration:** Portainer (bereits vorhanden)

### Architektur

```
/app
├── /public
│   ├── index.php          # Entry point
│   ├── /css
│   │   └── styles.css
│   ├── /js
│   │   └── app.js
│   └── /assets
│       └── /images
├── /src
│   ├── /Controllers
│   │   ├── AuthController.php
│   │   ├── PostController.php
│   │   ├── AccountController.php
│   │   └── AnalyticsController.php
│   ├── /Models
│   │   ├── User.php
│   │   ├── Post.php
│   │   ├── Account.php
│   │   └── Analytics.php
│   ├── /Services
│   │   ├── TwitterService.php
│   │   ├── InstagramService.php
│   │   ├── TikTokService.php
│   │   ├── LinkedInService.php
│   │   └── YouTubeService.php
│   ├── /Lib
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── Auth.php
│   │   └── HttpClient.php
│   └── config.php
├── /data
│   └── database.sqlite
├── /storage
│   └── /uploads
├── Dockerfile
├── docker-compose.yml
└── README.md
```

### Database Schema (SQLite)

```sql
-- Users
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    name TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Social Accounts
CREATE TABLE accounts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    platform TEXT NOT NULL, -- twitter, instagram, tiktok, linkedin, youtube
    platform_user_id TEXT,
    platform_username TEXT,
    access_token TEXT,
    refresh_token TEXT,
    token_expires_at DATETIME,
    account_group TEXT DEFAULT 'default',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Posts
CREATE TABLE posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    content TEXT NOT NULL,
    media_urls TEXT, -- JSON array
    status TEXT DEFAULT 'draft', -- draft, scheduled, published, failed
    scheduled_at DATETIME,
    published_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Post Platforms (which platforms a post goes to)
CREATE TABLE post_platforms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER NOT NULL,
    account_id INTEGER NOT NULL,
    platform_post_id TEXT, -- ID returned by platform after posting
    status TEXT DEFAULT 'pending', -- pending, published, failed
    error_message TEXT,
    published_at DATETIME,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (account_id) REFERENCES accounts(id)
);

-- Analytics
CREATE TABLE analytics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_platform_id INTEGER NOT NULL,
    likes INTEGER DEFAULT 0,
    comments INTEGER DEFAULT 0,
    shares INTEGER DEFAULT 0,
    views INTEGER DEFAULT 0,
    fetched_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_platform_id) REFERENCES post_platforms(id)
);

-- Templates
CREATE TABLE templates (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    content TEXT NOT NULL,
    hashtags TEXT, -- JSON array
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Hashtag Groups
CREATE TABLE hashtag_groups (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    hashtags TEXT NOT NULL, -- JSON array
    platform TEXT, -- optional: platform-specific
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## Docker Setup

### Dockerfile

```dockerfile
FROM php:8.3-apache

# Enable Apache modules
RUN a2enmod rewrite

# Install SQLite extension
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY ./app /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/data \
    && chmod -R 777 /var/www/html/storage

# Apache config
COPY apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
```

### docker-compose.yml

```yaml
version: '3.8'

services:
  postamt:
    build: .
    container_name: postamt
    restart: unless-stopped
    ports:
      - "10.8.0.1:8080:80"  # Nur über WireGuard erreichbar
    volumes:
      - ./app/data:/var/www/html/data
      - ./app/storage:/var/www/html/storage
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
```

### apache.conf

```apache
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    
    <Directory /var/www/html/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

---

## API Endpoints

### Auth
- `POST /api/auth/register` – User registrieren
- `POST /api/auth/login` – Login
- `POST /api/auth/logout` – Logout
- `GET /api/auth/me` – Current user

### Accounts
- `GET /api/accounts` – Alle verbundenen Accounts
- `POST /api/accounts/connect/{platform}` – OAuth starten
- `GET /api/accounts/callback/{platform}` – OAuth callback
- `DELETE /api/accounts/{id}` – Account trennen

### Posts
- `GET /api/posts` – Alle Posts (mit Filter)
- `POST /api/posts` – Neuen Post erstellen
- `GET /api/posts/{id}` – Einzelnen Post
- `PUT /api/posts/{id}` – Post bearbeiten
- `DELETE /api/posts/{id}` – Post löschen
- `POST /api/posts/{id}/publish` – Sofort veröffentlichen

### Analytics
- `GET /api/analytics/posts/{id}` – Analytics für einen Post
- `GET /api/analytics/overview` – Dashboard-Daten

### Templates
- `GET /api/templates` – Alle Templates
- `POST /api/templates` – Template erstellen
- `PUT /api/templates/{id}` – Template bearbeiten
- `DELETE /api/templates/{id}` – Template löschen

---

## UI Pages

1. **Landing Page** (public) – `/` – siehe HTML unten
2. **Login/Register** – `/login`, `/register`
3. **Dashboard** – `/dashboard` – Übersicht, Quick Actions
4. **Composer** – `/compose` – Post erstellen
5. **Calendar** – `/calendar` – Scheduled Posts
6. **Accounts** – `/accounts` – Verbundene Accounts verwalten
7. **Analytics** – `/analytics` – Performance-Daten
8. **Templates** – `/templates` – Gespeicherte Templates
9. **Settings** – `/settings` – User Settings

---

## Landing Page HTML

```html
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postamt – Social Media Content erstellen, überall posten</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0a0a0a;
            color: #fafafa;
            line-height: 1.6;
        }

        .gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(ellipse at 20% 20%, rgba(120, 80, 255, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(255, 100, 150, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(80, 200, 255, 0.05) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            position: relative;
            z-index: 1;
        }

        nav {
            padding: 24px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .nav-links a {
            color: #a1a1aa;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #fafafa;
        }

        .hero {
            padding: 80px 0 60px;
            text-align: center;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(167, 139, 250, 0.1);
            border: 1px solid rgba(167, 139, 250, 0.3);
            padding: 8px 16px;
            border-radius: 100px;
            font-size: 13px;
            color: #a78bfa;
            margin-bottom: 32px;
        }

        .badge::before {
            content: "✨";
        }

        h1 {
            font-size: clamp(40px, 8vw, 72px);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            letter-spacing: -0.02em;
        }

        h1 span {
            background: linear-gradient(135deg, #a78bfa, #f472b6, #fb923c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 20px;
            color: #a1a1aa;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .cta-box {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .email-input {
            padding: 16px 24px;
            font-size: 16px;
            border: 1px solid #27272a;
            border-radius: 12px;
            background: #18181b;
            color: #fafafa;
            width: 300px;
            outline: none;
            transition: border-color 0.2s;
        }

        .email-input:focus {
            border-color: #a78bfa;
        }

        .email-input::placeholder {
            color: #52525b;
        }

        .btn-primary {
            padding: 16px 32px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #a78bfa, #f472b6);
            color: #fafafa;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(167, 139, 250, 0.3);
        }

        .social-proof {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: #71717a;
            font-size: 14px;
        }

        .avatars {
            display: flex;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #0a0a0a;
            margin-left: -8px;
            background: linear-gradient(135deg, #6366f1, #a78bfa);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .avatar:first-child {
            margin-left: 0;
        }

        .platforms {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 60px;
            padding: 24px;
            background: rgba(39, 39, 42, 0.5);
            border-radius: 16px;
            border: 1px solid #27272a;
        }

        .platform {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: #71717a;
            font-size: 12px;
        }

        .platform-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: #18181b;
            border: 1px solid #27272a;
        }

        .problem {
            padding: 100px 0;
        }

        .problem h2 {
            font-size: 16px;
            color: #f472b6;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 16px;
        }

        .problem h3 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 48px;
        }

        .problem-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .problem-card {
            background: #18181b;
            border: 1px solid #27272a;
            border-radius: 16px;
            padding: 32px;
            transition: border-color 0.2s;
        }

        .problem-card:hover {
            border-color: #3f3f46;
        }

        .problem-card .icon {
            font-size: 32px;
            margin-bottom: 16px;
        }

        .problem-card h4 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .problem-card p {
            color: #a1a1aa;
            font-size: 15px;
        }

        .solution {
            padding: 100px 0;
            background: linear-gradient(180deg, transparent, rgba(167, 139, 250, 0.03), transparent);
        }

        .solution h2 {
            font-size: 16px;
            color: #a78bfa;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 16px;
            text-align: center;
        }

        .solution h3 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 16px;
            text-align: center;
        }

        .solution > p {
            text-align: center;
            color: #a1a1aa;
            max-width: 600px;
            margin: 0 auto 60px;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 32px;
        }

        .feature {
            background: #18181b;
            border: 1px solid #27272a;
            border-radius: 20px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .feature::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(167, 139, 250, 0.5), transparent);
        }

        .feature-number {
            font-size: 64px;
            font-weight: 800;
            background: linear-gradient(135deg, #27272a, #18181b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: absolute;
            top: 20px;
            right: 30px;
        }

        .feature h4 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .feature p {
            color: #a1a1aa;
            font-size: 15px;
            line-height: 1.7;
        }

        .feature-tag {
            display: inline-block;
            background: rgba(167, 139, 250, 0.1);
            color: #a78bfa;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 12px;
            margin-top: 16px;
        }

        .anti-slop {
            padding: 100px 0;
            text-align: center;
        }

        .anti-slop h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .anti-slop h2 span {
            text-decoration: line-through;
            color: #52525b;
        }

        .anti-slop p {
            color: #a1a1aa;
            max-width: 600px;
            margin: 0 auto 48px;
            font-size: 18px;
        }

        .comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            max-width: 800px;
            margin: 0 auto;
        }

        .comparison-card {
            padding: 32px;
            border-radius: 16px;
            text-align: left;
        }

        .comparison-card.bad {
            background: rgba(239, 68, 68, 0.05);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .comparison-card.good {
            background: rgba(34, 197, 94, 0.05);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .comparison-card h4 {
            font-size: 18px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .comparison-card.bad h4 {
            color: #ef4444;
        }

        .comparison-card.good h4 {
            color: #22c55e;
        }

        .comparison-card ul {
            list-style: none;
        }

        .comparison-card li {
            padding: 8px 0;
            color: #a1a1aa;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .comparison-card.bad li::before {
            content: "✕";
            color: #ef4444;
        }

        .comparison-card.good li::before {
            content: "✓";
            color: #22c55e;
        }

        .pricing {
            padding: 100px 0;
        }

        .pricing h2 {
            font-size: 16px;
            color: #a78bfa;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 16px;
            text-align: center;
        }

        .pricing h3 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 60px;
            text-align: center;
        }

        .pricing-card {
            max-width: 480px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(167, 139, 250, 0.1), rgba(244, 114, 182, 0.1));
            border: 1px solid rgba(167, 139, 250, 0.3);
            border-radius: 24px;
            padding: 48px;
            text-align: center;
            position: relative;
        }

        .pricing-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #a78bfa, #f472b6);
            padding: 6px 20px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
        }

        .price {
            margin: 24px 0;
        }

        .price .amount {
            font-size: 64px;
            font-weight: 800;
        }

        .price .period {
            color: #a1a1aa;
        }

        .price .original {
            text-decoration: line-through;
            color: #52525b;
            font-size: 24px;
            margin-right: 8px;
        }

        .pricing-features {
            text-align: left;
            margin: 32px 0;
            list-style: none;
        }

        .pricing-features li {
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 12px;
            color: #e4e4e7;
        }

        .pricing-features li::before {
            content: "✓";
            color: #22c55e;
            font-weight: bold;
        }

        .pricing-card .btn-primary {
            width: 100%;
            padding: 20px;
            font-size: 18px;
        }

        .final-cta {
            padding: 100px 0;
            text-align: center;
        }

        .final-cta h2 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 24px;
        }

        .final-cta p {
            color: #a1a1aa;
            font-size: 18px;
            margin-bottom: 40px;
        }

        footer {
            padding: 48px 0;
            border-top: 1px solid #27272a;
            text-align: center;
            color: #52525b;
            font-size: 14px;
        }

        footer a {
            color: #71717a;
            text-decoration: none;
        }

        footer a:hover {
            color: #a1a1aa;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .email-input {
                width: 100%;
            }

            .comparison {
                grid-template-columns: 1fr;
            }

            .platforms {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="gradient-bg"></div>

    <div class="container">
        <nav>
            <div class="logo">Postamt</div>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#pricing">Preise</a>
                <a href="/login">Login</a>
            </div>
        </nav>

        <section class="hero">
            <div class="badge">Beta – Erste 100 User bekommen Lifetime Access</div>
            
            <h1>
                Social Media Content.<br>
                <span>Ohne den ganzen Stress.</span>
            </h1>
            
            <p>
                Poste auf allen Plattformen gleichzeitig. Erstelle Content der funktioniert – 
                nicht AI-Slop. Für Creator die es ernst meinen.
            </p>

            <div class="cta-box">
                <input type="email" class="email-input" placeholder="deine@email.de" id="waitlist-email">
                <button class="btn-primary" onclick="joinWaitlist()">Frühen Zugang sichern →</button>
            </div>

            <div class="social-proof">
                <div class="avatars">
                    <div class="avatar">JK</div>
                    <div class="avatar">ML</div>
                    <div class="avatar">AS</div>
                    <div class="avatar">+</div>
                </div>
                <span id="waitlist-count">0 Creator auf der Warteliste</span>
            </div>

            <div class="platforms">
                <div class="platform">
                    <div class="platform-icon">📸</div>
                    <span>Instagram</span>
                </div>
                <div class="platform">
                    <div class="platform-icon">🎵</div>
                    <span>TikTok</span>
                </div>
                <div class="platform">
                    <div class="platform-icon">▶️</div>
                    <span>YouTube</span>
                </div>
                <div class="platform">
                    <div class="platform-icon">💼</div>
                    <span>LinkedIn</span>
                </div>
                <div class="platform">
                    <div class="platform-icon">𝕏</div>
                    <span>X / Twitter</span>
                </div>
            </div>
        </section>

        <section class="problem">
            <h2>Das Problem</h2>
            <h3>Social Media frisst deine Zeit. Jeden. Einzelnen. Tag.</h3>
            
            <div class="problem-grid">
                <div class="problem-card">
                    <div class="icon">⏰</div>
                    <h4>1-2 Stunden täglich verschwendet</h4>
                    <p>Zwischen Apps wechseln, Copy-Paste, Formate anpassen. Das gleiche Video 5x hochladen. Wahnsinn.</p>
                </div>
                <div class="problem-card">
                    <div class="icon">💸</div>
                    <h4>Tools die 150€/Monat kosten</h4>
                    <p>Hootsuite, Sprout Social, Later Pro... Enterprise-Preise für Funktionen die du nicht brauchst.</p>
                </div>
                <div class="problem-card">
                    <div class="icon">🤖</div>
                    <h4>AI-Slop überall</h4>
                    <p>Jedes Tool will "AI-Generated Content". Das Ergebnis? Generischer Müll den niemand liest.</p>
                </div>
            </div>
        </section>

        <section class="solution" id="features">
            <h2>Die Lösung</h2>
            <h3>Ein Tool das dir hilft. Nicht für dich denkt.</h3>
            <p>Du behältst die kreative Kontrolle. Wir sparen dir Zeit.</p>

            <div class="features">
                <div class="feature">
                    <span class="feature-number">01</span>
                    <h4>Ein Post. Alle Plattformen.</h4>
                    <p>Schreib einmal, poste überall. Automatische Anpassung an jedes Format – ohne Copy-Paste-Hölle.</p>
                    <span class="feature-tag">30 Sekunden statt 30 Minuten</span>
                </div>
                <div class="feature">
                    <span class="feature-number">02</span>
                    <h4>Smart Scheduling</h4>
                    <p>Plane deine Woche in 15 Minuten. Wir zeigen dir wann deine Audience online ist – du entscheidest.</p>
                    <span class="feature-tag">Datenbasiert</span>
                </div>
                <div class="feature">
                    <span class="feature-number">03</span>
                    <h4>Content-Werkzeuge</h4>
                    <p>Hook-Vorschläge, Hashtag-Recherche, Thumbnail-Ideen. AI als Sparringspartner – nicht als Autopilot.</p>
                    <span class="feature-tag">Du bleibst authentisch</span>
                </div>
                <div class="feature">
                    <span class="feature-number">04</span>
                    <h4>Analytics die Sinn machen</h4>
                    <p>Keine 47 Dashboards. Eine Zahl: Was hat funktioniert und warum. Fertig.</p>
                    <span class="feature-tag">Kein Data-Overload</span>
                </div>
            </div>
        </section>

        <section class="anti-slop">
            <h2><span>AI-Slop</span> Authentischer Content</h2>
            <p>
                Der Unterschied zwischen Tools die für dich posten und Tools die dir helfen besser zu posten.
            </p>

            <div class="comparison">
                <div class="comparison-card bad">
                    <h4>❌ AI-Autopilot Tools</h4>
                    <ul>
                        <li>Generieren komplette Posts für dich</li>
                        <li>Klingen wie jeder andere Bot</li>
                        <li>Null persönliche Stimme</li>
                        <li>Audience merkt es sofort</li>
                        <li>Engagement sinkt langfristig</li>
                    </ul>
                </div>
                <div class="comparison-card good">
                    <h4>✓ Unser Ansatz</h4>
                    <ul>
                        <li>Du schreibst, wir sparen dir Zeit</li>
                        <li>Vorschläge die DU anpasst</li>
                        <li>Deine Stimme, dein Stil</li>
                        <li>Echte Verbindung zu deiner Audience</li>
                        <li>Nachhaltiges Engagement</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="pricing" id="pricing">
            <h2>Preise</h2>
            <h3>Einfach. Fair. Kein Enterprise-Bullshit.</h3>

            <div class="pricing-card">
                <div class="pricing-badge">🔥 Early Access Deal</div>
                
                <h4>Creator Plan</h4>
                
                <div class="price">
                    <span class="original">€19</span>
                    <span class="amount">€9</span>
                    <span class="period">/Monat</span>
                </div>

                <ul class="pricing-features">
                    <li>5 Social Media Accounts</li>
                    <li>Unbegrenzte Posts</li>
                    <li>Alle Plattformen (Insta, TikTok, YT, LinkedIn, X)</li>
                    <li>Smart Scheduling</li>
                    <li>Content-Werkzeuge & Hook-Vorschläge</li>
                    <li>Analytics Dashboard</li>
                    <li>Support von einem echten Menschen</li>
                </ul>

                <button class="btn-primary" onclick="joinWaitlist()">Jetzt Early Access sichern →</button>
                
                <p style="margin-top: 16px; font-size: 13px; color: #71717a;">
                    Erste 100 User: Dieser Preis bleibt für immer.
                </p>
            </div>
        </section>

        <section class="final-cta">
            <h2>Bereit?</h2>
            <p>
                Hör auf Zeit zu verschwenden. Fang an Content zu machen der funktioniert.
            </p>
            <div class="cta-box">
                <input type="email" class="email-input" placeholder="deine@email.de" id="waitlist-email-2">
                <button class="btn-primary" onclick="joinWaitlist()">Auf die Warteliste →</button>
            </div>
        </section>
    </div>

    <footer>
        <div class="container">
            <p>
                Gebaut mit ❤️ für Creator · Made in Germany 🇩🇪
            </p>
        </div>
    </footer>

    <script>
        async function joinWaitlist() {
            const email = document.getElementById('waitlist-email').value || 
                          document.getElementById('waitlist-email-2').value;
            
            if (!email || !email.includes('@')) {
                alert('Bitte gib eine gültige Email-Adresse ein.');
                return;
            }

            try {
                const response = await fetch('/api/waitlist', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email })
                });

                if (response.ok) {
                    alert('🎉 Du bist auf der Warteliste! Wir melden uns bald.');
                    document.getElementById('waitlist-email').value = '';
                    document.getElementById('waitlist-email-2').value = '';
                    loadWaitlistCount();
                } else {
                    const data = await response.json();
                    alert(data.error || 'Etwas ist schiefgelaufen.');
                }
            } catch (err) {
                alert('Fehler beim Verbinden. Bitte versuche es später.');
            }
        }

        async function loadWaitlistCount() {
            try {
                const response = await fetch('/api/waitlist/count');
                const data = await response.json();
                document.getElementById('waitlist-count').textContent = 
                    `${data.count} Creator auf der Warteliste`;
            } catch (err) {
                console.error('Could not load waitlist count');
            }
        }

        loadWaitlistCount();
    </script>
</body>
</html>
```

---

## Waitlist API (für Landing Page)

Simples PHP-Endpoint für die Warteliste:

```php
<?php
// /public/api/waitlist.php

header('Content-Type: application/json');

$db = new PDO('sqlite:../data/database.sqlite');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email']);
        exit;
    }
    
    // Check if already exists
    $stmt = $db->prepare('SELECT id FROM waitlist WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Already on waitlist']);
        exit;
    }
    
    // Insert
    $stmt = $db->prepare('INSERT INTO waitlist (email, created_at) VALUES (?, datetime("now"))');
    $stmt->execute([$email]);
    
    echo json_encode(['success' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '/count') !== false) {
    $stmt = $db->query('SELECT COUNT(*) as count FROM waitlist');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['count' => (int)$result['count']]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
```

---

## Deployment Anleitung

### 1. Projekt klonen/erstellen
```bash
mkdir -p /opt/social-tool
cd /opt/social-tool
# Dateien hier platzieren
```

### 2. Docker Image bauen
```bash
docker build -t social-tool .
```

### 3. Container starten
```bash
docker-compose up -d
```

### 4. Datenbank initialisieren
```bash
docker exec -it social-tool php /var/www/html/src/init-db.php
```

### 5. In Portainer verwalten
Container erscheint automatisch in Portainer unter `social-tool`.

---

## Programmatic SEO

### Strategie

Automatisch generierte Landing Pages für jede relevante Keyword-Kombination. Jede Seite ist einzigartig, hat echten Content und rankt für spezifische Longtail-Keywords.

### URL-Struktur

```
/tools/[plattform]-scheduler
/tools/[plattform]-post-planen
/vergleich/[tool]-alternative
/guides/[plattform]-und-[plattform]-gleichzeitig-posten
```

### Keyword-Matrix

#### Plattform-spezifische Seiten
Für jede Plattform eine eigene Landing Page:

| Plattform | URLs |
|-----------|------|
| Instagram | `/tools/instagram-scheduler`, `/tools/instagram-post-planen`, `/tools/instagram-reels-planen` |
| TikTok | `/tools/tiktok-scheduler`, `/tools/tiktok-post-planen`, `/tools/tiktok-videos-planen` |
| LinkedIn | `/tools/linkedin-scheduler`, `/tools/linkedin-post-planen`, `/tools/linkedin-beitraege-planen` |
| YouTube | `/tools/youtube-scheduler`, `/tools/youtube-shorts-planen`, `/tools/youtube-videos-planen` |
| Twitter/X | `/tools/twitter-scheduler`, `/tools/x-post-planen`, `/tools/tweets-planen` |
| Threads | `/tools/threads-scheduler`, `/tools/threads-post-planen` |
| Bluesky | `/tools/bluesky-scheduler`, `/tools/bluesky-post-planen` |

#### Kombinations-Seiten (Multi-Platform)
Für jede sinnvolle Plattform-Kombination:

```
/tools/instagram-und-tiktok-gleichzeitig-posten
/tools/instagram-und-linkedin-gleichzeitig-posten
/tools/tiktok-und-youtube-shorts-gleichzeitig-posten
/tools/alle-social-media-gleichzeitig-posten
/tools/mehrere-social-media-accounts-verwalten
```

#### Vergleichs-Seiten (Competitor Keywords)
```
/vergleich/hootsuite-alternative
/vergleich/buffer-alternative
/vergleich/later-alternative
/vergleich/sprout-social-alternative
/vergleich/planoly-alternative
/vergleich/hootsuite-vs-postamt
/vergleich/buffer-vs-postamt
```

#### Zielgruppen-Seiten
```
/fuer/creator
/fuer/influencer
/fuer/kleine-unternehmen
/fuer/agenturen
/fuer/freelancer
/fuer/content-creator
```

#### Guide-Seiten (Informational)
```
/guides/beste-zeit-zum-posten-instagram
/guides/beste-zeit-zum-posten-tiktok
/guides/beste-zeit-zum-posten-linkedin
/guides/social-media-content-kalender-erstellen
/guides/wie-viele-posts-pro-woche
/guides/hashtag-strategie-instagram
/guides/hashtag-strategie-tiktok
```

### Datenbank-Schema für SEO-Seiten

```sql
-- SEO Landing Pages
CREATE TABLE seo_pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT UNIQUE NOT NULL,
    page_type TEXT NOT NULL, -- 'platform', 'combination', 'comparison', 'audience', 'guide'
    title TEXT NOT NULL,
    meta_description TEXT NOT NULL,
    h1 TEXT NOT NULL,
    intro_text TEXT NOT NULL,
    main_content TEXT NOT NULL,
    features_json TEXT, -- JSON array of features to highlight
    faqs_json TEXT, -- JSON array of FAQs
    related_pages_json TEXT, -- JSON array of related page slugs
    primary_keyword TEXT NOT NULL,
    secondary_keywords TEXT, -- JSON array
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- SEO Page Templates
CREATE TABLE seo_templates (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page_type TEXT UNIQUE NOT NULL,
    title_template TEXT NOT NULL, -- z.B. "{platform} Scheduler – Posts planen mit Postamt"
    meta_template TEXT NOT NULL,
    h1_template TEXT NOT NULL,
    intro_template TEXT NOT NULL,
    content_template TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Template-System

Jeder Seitentyp hat ein Template mit Variablen:

#### Platform Template
```php
$templates['platform'] = [
    'title' => '{platform} Scheduler – Posts planen & automatisch posten | Postamt',
    'meta_description' => '{platform} Posts planen und automatisch veröffentlichen. Mit Postamt sparst du Zeit und postest auf {platform} und anderen Plattformen gleichzeitig. Jetzt kostenlos testen.',
    'h1' => '{platform} Posts planen & automatisch posten',
    'intro' => 'Mit Postamt planst du deine {platform} Posts im Voraus und veröffentlichst sie automatisch zur besten Zeit. Spare 1-2 Stunden täglich und poste gleichzeitig auf allen Plattformen.',
];
```

#### Comparison Template
```php
$templates['comparison'] = [
    'title' => '{competitor} Alternative – Postamt vs {competitor} Vergleich 2025',
    'meta_description' => 'Suchst du eine {competitor} Alternative? Postamt bietet die gleichen Features für weniger Geld. Vergleiche jetzt {competitor} vs Postamt.',
    'h1' => 'Die beste {competitor} Alternative: Postamt',
    'intro' => '{competitor} ist teuer und kompliziert. Postamt macht Social Media Management einfach – für einen Bruchteil des Preises.',
];
```

#### Combination Template
```php
$templates['combination'] = [
    'title' => '{platform1} und {platform2} gleichzeitig posten | Postamt',
    'meta_description' => 'Poste auf {platform1} und {platform2} gleichzeitig mit einem Klick. Postamt spart dir Zeit beim Cross-Posting. Jetzt kostenlos testen.',
    'h1' => '{platform1} und {platform2} gleichzeitig posten',
    'intro' => 'Schluss mit Copy-Paste zwischen {platform1} und {platform2}. Mit Postamt erstellst du einen Post und veröffentlichst ihn auf beiden Plattformen gleichzeitig.',
];
```

### Seiten-Struktur (HTML)

Jede SEO-Seite folgt dieser Struktur:

```html
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title}</title>
    <meta name="description" content="{meta_description}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://postamt.ai{slug}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{title}">
    <meta property="og:description" content="{meta_description}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://postamt.ai{slug}">
    
    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Postamt",
        "applicationCategory": "Social Media Management",
        "operatingSystem": "Web",
        "offers": {
            "@type": "Offer",
            "price": "9",
            "priceCurrency": "EUR"
        }
    }
    </script>
</head>
<body>
    <!-- Navigation -->
    <nav>...</nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <h1>{h1}</h1>
        <p class="intro">{intro_text}</p>
        <div class="cta">
            <a href="/signup" class="btn-primary">Kostenlos testen</a>
        </div>
    </section>
    
    <!-- Problem Section -->
    <section class="problem">
        <h2>Das Problem mit {context}</h2>
        <p>{problem_text}</p>
    </section>
    
    <!-- Solution/Features Section -->
    <section class="features">
        <h2>So hilft dir Postamt</h2>
        <!-- Dynamic features based on page type -->
    </section>
    
    <!-- How It Works -->
    <section class="how-it-works">
        <h2>So funktioniert's</h2>
        <ol>
            <li>Account verbinden</li>
            <li>Post erstellen</li>
            <li>Plattformen wählen</li>
            <li>Veröffentlichen oder planen</li>
        </ol>
    </section>
    
    <!-- Comparison Table (for comparison pages) -->
    <section class="comparison-table">
        <h2>Postamt vs {competitor}</h2>
        <table>...</table>
    </section>
    
    <!-- Testimonials -->
    <section class="testimonials">
        <h2>Das sagen unsere Nutzer</h2>
    </section>
    
    <!-- FAQ Section (important for SEO) -->
    <section class="faq">
        <h2>Häufige Fragen</h2>
        <!-- FAQ Schema markup -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": [...]
        }
        </script>
    </section>
    
    <!-- CTA Section -->
    <section class="final-cta">
        <h2>Bereit loszulegen?</h2>
        <a href="/signup" class="btn-primary">Jetzt kostenlos starten</a>
    </section>
    
    <!-- Internal Links -->
    <section class="related">
        <h2>Ähnliche Tools</h2>
        <ul>
            <!-- Links to related SEO pages -->
        </ul>
    </section>
    
    <!-- Footer -->
    <footer>...</footer>
</body>
</html>
```

### PHP Router für SEO-Seiten

```php
<?php
// /public/index.php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// SEO Page Routes
$seoPatterns = [
    '#^/tools/([a-z-]+)$#' => 'renderToolPage',
    '#^/vergleich/([a-z-]+)$#' => 'renderComparisonPage',
    '#^/fuer/([a-z-]+)$#' => 'renderAudiencePage',
    '#^/guides/([a-z-]+)$#' => 'renderGuidePage',
];

foreach ($seoPatterns as $pattern => $handler) {
    if (preg_match($pattern, $uri, $matches)) {
        $slug = $matches[1];
        $handler($slug);
        exit;
    }
}

// Fallback to normal routing
// ...

function renderToolPage($slug) {
    $db = new PDO('sqlite:../data/database.sqlite');
    $stmt = $db->prepare('SELECT * FROM seo_pages WHERE slug = ?');
    $stmt->execute(['/tools/' . $slug]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$page) {
        http_response_code(404);
        include '../templates/404.php';
        return;
    }
    
    include '../templates/seo-page.php';
}
```

### Seiten-Generator Script

```php
<?php
// /src/generate-seo-pages.php

$db = new PDO('sqlite:../data/database.sqlite');

// Platform data
$platforms = [
    'instagram' => ['name' => 'Instagram', 'icon' => '📸', 'features' => ['Reels', 'Stories', 'Posts', 'Karussell']],
    'tiktok' => ['name' => 'TikTok', 'icon' => '🎵', 'features' => ['Videos', 'Sounds', 'Trends']],
    'linkedin' => ['name' => 'LinkedIn', 'icon' => '💼', 'features' => ['Posts', 'Artikel', 'Newsletter']],
    'youtube' => ['name' => 'YouTube', 'icon' => '▶️', 'features' => ['Shorts', 'Videos', 'Community Posts']],
    'twitter' => ['name' => 'X/Twitter', 'icon' => '𝕏', 'features' => ['Tweets', 'Threads', 'Spaces']],
    'threads' => ['name' => 'Threads', 'icon' => '🧵', 'features' => ['Posts', 'Replies']],
    'bluesky' => ['name' => 'Bluesky', 'icon' => '🦋', 'features' => ['Posts', 'Feeds']],
];

// Competitors
$competitors = [
    'hootsuite' => ['name' => 'Hootsuite', 'price' => '99€/Monat'],
    'buffer' => ['name' => 'Buffer', 'price' => '15€/Monat'],
    'later' => ['name' => 'Later', 'price' => '25€/Monat'],
    'sprout-social' => ['name' => 'Sprout Social', 'price' => '249€/Monat'],
    'planoly' => ['name' => 'Planoly', 'price' => '13€/Monat'],
];

// Generate platform pages
foreach ($platforms as $slug => $platform) {
    generatePlatformPage($db, $slug, $platform);
}

// Generate combination pages
$combos = [
    ['instagram', 'tiktok'],
    ['instagram', 'linkedin'],
    ['tiktok', 'youtube'],
    ['instagram', 'twitter'],
    ['linkedin', 'twitter'],
];

foreach ($combos as $combo) {
    generateCombinationPage($db, $combo[0], $combo[1], $platforms);
}

// Generate comparison pages
foreach ($competitors as $slug => $competitor) {
    generateComparisonPage($db, $slug, $competitor);
}

function generatePlatformPage($db, $slug, $platform) {
    $data = [
        'slug' => '/tools/' . $slug . '-scheduler',
        'page_type' => 'platform',
        'title' => $platform['name'] . ' Scheduler – Posts planen & automatisch posten | Postamt',
        'meta_description' => $platform['name'] . ' Posts planen und automatisch veröffentlichen. Mit Postamt sparst du Zeit und postest auf ' . $platform['name'] . ' und anderen Plattformen gleichzeitig.',
        'h1' => $platform['name'] . ' Posts planen & automatisch posten',
        'intro_text' => 'Mit Postamt planst du deine ' . $platform['name'] . ' Posts im Voraus und veröffentlichst sie automatisch zur besten Zeit.',
        'main_content' => generatePlatformContent($platform),
        'features_json' => json_encode($platform['features']),
        'primary_keyword' => $slug . ' scheduler',
    ];
    
    insertOrUpdatePage($db, $data);
}

function generateCombinationPage($db, $platform1, $platform2, $platforms) {
    $p1 = $platforms[$platform1];
    $p2 = $platforms[$platform2];
    
    $data = [
        'slug' => '/tools/' . $platform1 . '-und-' . $platform2 . '-gleichzeitig-posten',
        'page_type' => 'combination',
        'title' => $p1['name'] . ' und ' . $p2['name'] . ' gleichzeitig posten | Postamt',
        'meta_description' => 'Poste auf ' . $p1['name'] . ' und ' . $p2['name'] . ' gleichzeitig mit einem Klick. Postamt spart dir Zeit beim Cross-Posting.',
        'h1' => $p1['name'] . ' und ' . $p2['name'] . ' gleichzeitig posten',
        'intro_text' => 'Schluss mit Copy-Paste zwischen ' . $p1['name'] . ' und ' . $p2['name'] . '. Mit Postamt erstellst du einen Post und veröffentlichst ihn auf beiden Plattformen gleichzeitig.',
        'main_content' => generateCombinationContent($p1, $p2),
        'primary_keyword' => $platform1 . ' und ' . $platform2 . ' gleichzeitig posten',
    ];
    
    insertOrUpdatePage($db, $data);
}

function generateComparisonPage($db, $slug, $competitor) {
    $data = [
        'slug' => '/vergleich/' . $slug . '-alternative',
        'page_type' => 'comparison',
        'title' => $competitor['name'] . ' Alternative 2025 – Günstiger & einfacher | Postamt',
        'meta_description' => 'Suchst du eine ' . $competitor['name'] . ' Alternative? Postamt bietet die gleichen Features für 9€/Monat statt ' . $competitor['price'] . '.',
        'h1' => 'Die beste ' . $competitor['name'] . ' Alternative: Postamt',
        'intro_text' => $competitor['name'] . ' kostet ' . $competitor['price'] . ' und ist oft zu kompliziert. Postamt macht Social Media Management einfach – für nur 9€/Monat.',
        'main_content' => generateComparisonContent($competitor),
        'primary_keyword' => $slug . ' alternative',
    ];
    
    insertOrUpdatePage($db, $data);
}

function insertOrUpdatePage($db, $data) {
    $stmt = $db->prepare('
        INSERT INTO seo_pages (slug, page_type, title, meta_description, h1, intro_text, main_content, features_json, primary_keyword, updated_at)
        VALUES (:slug, :page_type, :title, :meta_description, :h1, :intro_text, :main_content, :features_json, :primary_keyword, datetime("now"))
        ON CONFLICT(slug) DO UPDATE SET
            title = :title,
            meta_description = :meta_description,
            h1 = :h1,
            intro_text = :intro_text,
            main_content = :main_content,
            features_json = :features_json,
            primary_keyword = :primary_keyword,
            updated_at = datetime("now")
    ');
    $stmt->execute($data);
}

// Content generators
function generatePlatformContent($platform) {
    return "
## Warum {$platform['name']} Posts planen?

Regelmäßiges Posten auf {$platform['name']} ist der Schlüssel zu mehr Reichweite. Aber wer hat schon Zeit, jeden Tag manuell zu posten?

Mit Postamt planst du deine {$platform['name']} Posts für die ganze Woche in 15 Minuten. Wähle die beste Zeit, und Postamt postet automatisch.

## Features für {$platform['name']}

- **Automatisches Posting** – Plane Posts im Voraus
- **Beste Zeit zum Posten** – Basierend auf deinen Daten
- **Multi-Format Support** – " . implode(', ', $platform['features']) . "
- **Cross-Posting** – Gleichzeitig auf anderen Plattformen posten

## So planst du {$platform['name']} Posts mit Postamt

1. Verbinde deinen {$platform['name']} Account
2. Erstelle deinen Post (Text, Bild, Video)
3. Wähle Datum und Uhrzeit
4. Fertig – Postamt übernimmt den Rest
    ";
}

function generateCombinationContent($p1, $p2) {
    return "
## Das Problem: Doppelte Arbeit

Du erstellst einen Post für {$p1['name']}. Dann öffnest du {$p2['name']}, kopierst den Text, passt ihn an, lädst das Bild nochmal hoch... Das dauert ewig.

## Die Lösung: Ein Klick, beide Plattformen

Mit Postamt erstellst du einen Post und wählst einfach beide Plattformen aus. Postamt passt das Format automatisch an und postet gleichzeitig.

## Was Postamt automatisch anpasst

- **Zeichenlimit** – {$p1['name']} und {$p2['name']} haben unterschiedliche Limits
- **Hashtags** – Optimiert für jede Plattform
- **Bildformat** – Automatisches Cropping
- **Mentions** – Plattform-spezifische @-Handles

## Zeit gespart

| Ohne Postamt | Mit Postamt |
|--------------|-------------|
| 15-20 Min pro Post | 2 Minuten |
| Manuelles Copy-Paste | Ein Klick |
| Fehleranfällig | Automatisch |
    ";
}

function generateComparisonContent($competitor) {
    return "
## Warum eine {$competitor['name']} Alternative suchen?

{$competitor['name']} ist ein gutes Tool, aber:

- **Teuer** – {$competitor['price']} für Features die du nicht brauchst
- **Kompliziert** – Überladenes Interface
- **Enterprise-fokussiert** – Nicht für Creator gemacht

## Postamt vs {$competitor['name']}

| Feature | Postamt | {$competitor['name']} |
|---------|---------|----------------------|
| Preis | 9€/Monat | {$competitor['price']} |
| Social Accounts | 5 | Begrenzt |
| Alle Plattformen | ✓ | Teilweise |
| Einfache UI | ✓ | Kompliziert |
| Für Creator | ✓ | Enterprise |

## Wann {$competitor['name']}, wann Postamt?

**Wähle {$competitor['name']} wenn:**
- Du ein großes Team hast
- Du Enterprise-Features brauchst
- Budget keine Rolle spielt

**Wähle Postamt wenn:**
- Du Creator oder Solopreneur bist
- Du einfach nur posten willst
- Du Geld sparen willst
    ";
}
```

### Sitemap Generator

```php
<?php
// /src/generate-sitemap.php

$db = new PDO('sqlite:../data/database.sqlite');
$stmt = $db->query('SELECT slug, updated_at FROM seo_pages');
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$xml = '<?xml version="1.0" encoding="UTF-8"?>';
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Homepage
$xml .= '<url><loc>https://postamt.ai/</loc><priority>1.0</priority></url>';

// SEO Pages
foreach ($pages as $page) {
    $xml .= '<url>';
    $xml .= '<loc>https://postamt.ai' . $page['slug'] . '</loc>';
    $xml .= '<lastmod>' . date('Y-m-d', strtotime($page['updated_at'])) . '</lastmod>';
    $xml .= '<priority>0.8</priority>';
    $xml .= '</url>';
}

$xml .= '</urlset>';

file_put_contents('../public/sitemap.xml', $xml);
echo "Sitemap generated with " . (count($pages) + 1) . " URLs\n";
```

### Internal Linking Strategie

Jede SEO-Seite verlinkt zu:
- 3-5 verwandten Tool-Seiten
- 1-2 Vergleichs-Seiten
- 1-2 Guide-Seiten
- Immer zur Hauptseite

```php
function getRelatedPages($db, $currentSlug, $pageType) {
    $related = [];
    
    // Get pages of same type
    $stmt = $db->prepare('
        SELECT slug, title FROM seo_pages 
        WHERE page_type = ? AND slug != ? 
        ORDER BY RANDOM() LIMIT 3
    ');
    $stmt->execute([$pageType, $currentSlug]);
    $related = array_merge($related, $stmt->fetchAll(PDO::FETCH_ASSOC));
    
    // Get comparison pages
    $stmt = $db->prepare('
        SELECT slug, title FROM seo_pages 
        WHERE page_type = "comparison" 
        ORDER BY RANDOM() LIMIT 2
    ');
    $stmt->execute();
    $related = array_merge($related, $stmt->fetchAll(PDO::FETCH_ASSOC));
    
    return $related;
}
```

### Deployment-Befehl für SEO-Seiten

Nach dem Deployment ausführen:

```bash
# SEO-Seiten generieren
docker exec -it postamt php /var/www/html/src/generate-seo-pages.php

# Sitemap generieren
docker exec -it postamt php /var/www/html/src/generate-sitemap.php

# Bei Google einreichen
# https://search.google.com/search-console
```

### Erwartete Seiten-Anzahl

| Typ | Anzahl |
|-----|--------|
| Platform-Seiten | 7 × 3 = 21 |
| Kombinations-Seiten | ~15 |
| Vergleichs-Seiten | ~10 |
| Zielgruppen-Seiten | ~6 |
| Guide-Seiten | ~10 |
| **Gesamt** | **~60 Seiten** |

60 indexierte Seiten mit je 500-1000 Wörtern unique Content = solide SEO-Basis.

---

## Nächste Schritte

1. [ ] Tool-Namen finalisieren
2. [ ] Domain kaufen
3. [ ] Landing Page deployen
4. [ ] Waitlist sammeln
5. [ ] MVP bauen (Phase 1)
6. [ ] Beta-Tester einladen
7. [ ] Iterieren basierend auf Feedback

---

## Notizen

- **Name:** Postamt.ai
- **Fokus auf Authentizität** – Kein AI-Autopilot, sondern AI-Assistenz
- **Fair Pricing** – €9/Monat für Early Access, später €19/Monat
- **Stack bewusst simpel** – PHP + SQLite, keine Abhängigkeiten
- **Programmatic SEO** – ~60 Landing Pages für organischen Traffic
