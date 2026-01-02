# Postamt.ai - Implementierungsplan

> **Version:** 1.0
> **Erstellt:** Januar 2026
> **Status:** In Entwicklung

---

## Inhaltsverzeichnis

1. [Produktvision & Beschreibung](#1-produktvision--beschreibung)
2. [Aktueller Stand](#2-aktueller-stand)
3. [Kern-Feature 1: Post Scheduling](#3-kern-feature-1-post-scheduling)
4. [Kern-Feature 2: Authentifizierung](#4-kern-feature-2-authentifizierung)
5. [Technische Architektur](#5-technische-architektur)
6. [Implementierungsreihenfolge](#6-implementierungsreihenfolge)
7. [API-Spezifikation](#7-api-spezifikation)
8. [Datenbank-Schema](#8-datenbank-schema)
9. [Sicherheitskonzept](#9-sicherheitskonzept)
10. [Testing-Strategie](#10-testing-strategie)
11. [Deployment & Operations](#11-deployment--operations)

---

## 1. Produktvision & Beschreibung

### 1.1 Was ist Postamt.ai?

**Postamt.ai** ist eine Social Media Management Plattform, die es Creators, Unternehmern und kleinen Teams ermöglicht, ihre Social Media Präsenz effizient zu verwalten. Der Name "Postamt" (deutsch für "Post Office") symbolisiert die zentrale Stelle, von der aus alle "Sendungen" (Posts) zu verschiedenen Zielen (Plattformen) verschickt werden.

### 1.2 Kernproblem das gelöst wird

Social Media Manager und Creator stehen vor folgenden Herausforderungen:

1. **Fragmentierung**: Jede Plattform hat eine eigene App/Website zum Posten
2. **Zeitaufwand**: Manuelles Cross-Posting auf 5+ Plattformen ist extrem zeitintensiv
3. **Inkonsistenz**: Unterschiedliche Posting-Zeiten führen zu suboptimaler Reichweite
4. **Komplexität**: Bestehende Tools sind überladen mit Features und teuer
5. **Planung**: Ohne Kalender-Übersicht fehlt die strategische Content-Planung

### 1.3 Die Postamt.ai Lösung

**Ein Post. Alle Plattformen. 15 Minuten für die ganze Woche.**

| Feature | Beschreibung |
|---------|--------------|
| **Cross-Posting** | Ein Post wird automatisch für alle verbundenen Plattformen formatiert und veröffentlicht |
| **Smart Scheduling** | Intelligente Zeitvorschläge basierend auf Audience-Aktivität |
| **Kalender-Übersicht** | Visuelle Wochenplanung per Drag-and-Drop |
| **Content-Werkzeuge** | Hook-Vorschläge, Hashtag-Gruppen, Templates |
| **Analytics** | Plattformübergreifende Performance-Metriken |

### 1.4 Unterstützte Plattformen

| Plattform | Priorität | Post-Typen | API-Status |
|-----------|-----------|------------|------------|
| **X (Twitter)** | P1 | Text, Bilder, Videos | OAuth 2.0 verfügbar |
| **Instagram** | P1 | Bilder, Stories, Reels | Meta Graph API |
| **TikTok** | P1 | Videos | Content Posting API |
| **LinkedIn** | P2 | Text, Bilder, Artikel | OAuth 2.0 verfügbar |
| **YouTube** | P2 | Videos, Shorts, Community | Google API |
| **Bluesky** | P2 | Text, Bilder | AT Protocol |
| **Snapchat** | P3 | Stories | Snap Kit |
| **WhatsApp** | P3 | Status | Business API |
| **Telegram** | P3 | Channel Posts | Bot API |

### 1.5 Zielgruppe

1. **Solo-Creator** (YouTube, TikTok, Instagram): Brauchen einfaches Tool ohne Schnickschnack
2. **Freelancer & Berater**: Managen eigene + Kunden-Accounts
3. **Kleine Unternehmen**: Marketing-Team von 1-3 Personen
4. **Startups**: Schnell wachsend, brauchen skalierbare Lösung

### 1.6 Unique Selling Proposition (USP)

1. **Einfachheit**: Kein Feature-Bloat, nur das Wesentliche
2. **Deutscher Support**: Lokaler Kundenservice auf Deutsch
3. **Fairer Preis**: 9€/Monat statt 30-50€ bei Konkurrenz
4. **Schnelligkeit**: Woche planen in 15 Minuten
5. **Datenschutz**: Server in Deutschland, DSGVO-konform

---

## 2. Aktueller Stand

### 2.1 Was bereits implementiert ist

#### Vollständig (95%+)
- [x] Datenbank-Schema (SQLite)
- [x] User Authentication (Register, Login, Logout)
- [x] Session Management
- [x] Router & HTTP Client Libraries
- [x] Landing Page mit Waitlist
- [x] Alle UI-Views (Dashboard, Compose, Calendar, Accounts, Analytics, Templates, Settings)
- [x] Responsive CSS-Styling
- [x] SEO-Infrastruktur
- [x] **Google OAuth Login** (Januar 2026)
- [x] **Post CRUD API** (Januar 2026)
- [x] **Twitter/X OAuth Integration** (Januar 2026)
- [x] **Scheduling Worker** (Januar 2026)
- [x] **Compose Page mit API-Anbindung** (Januar 2026)
- [x] **Accounts Page funktionsfähig** (Januar 2026)

#### In Bearbeitung
- [ ] Twitter API Credentials konfigurieren (benötigt Zugang zu X Developer Portal)
- [ ] Cron Job auf Server einrichten

#### Nicht begonnen
- [ ] Media Upload & Storage
- [ ] Instagram Integration (Meta Graph API)
- [ ] TikTok Integration
- [ ] LinkedIn Integration
- [ ] YouTube Integration
- [ ] Analytics Daten abrufen
- [ ] Templates CRUD
- [ ] Settings speichern

### 2.2 Technologie-Stack

```
┌─────────────────────────────────────────────────────────┐
│                      FRONTEND                           │
│  HTML/CSS/Vanilla JS │ Responsive │ Light Mode          │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                       BACKEND                           │
│  PHP 8.x │ Custom Router │ PDO │ Session Auth           │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                      DATABASE                           │
│  SQLite (Development) │ PostgreSQL/MySQL (Production)   │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                   EXTERNAL SERVICES                     │
│  Twitter API │ Meta API │ TikTok API │ LinkedIn API     │
│  Google OAuth │ AWS S3/CloudFlare R2 (Media Storage)    │
└─────────────────────────────────────────────────────────┘
```

---

## 3. Kern-Feature 1: Post Scheduling

### 3.1 Feature-Übersicht

Das Post Scheduling Feature ermöglicht es Nutzern:

1. **Posts zu erstellen** mit Text, Bildern und Videos
2. **Plattformen auszuwählen** auf denen der Post erscheinen soll
3. **Zeitpunkt festzulegen** (sofort oder geplant)
4. **Posts zu verwalten** (bearbeiten, löschen, duplizieren)
5. **Kalender-Übersicht** aller geplanten Posts

### 3.2 User Flow

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Compose   │────▶│   Preview   │────▶│   Schedule  │
│   Content   │     │   Check     │     │   or Post   │
└─────────────┘     └─────────────┘     └─────────────┘
                                               │
                    ┌──────────────────────────┼──────────────────────────┐
                    ▼                          ▼                          ▼
             ┌─────────────┐           ┌─────────────┐           ┌─────────────┐
             │   Post Now  │           │   Schedule  │           │ Save Draft  │
             │   (sofort)  │           │   (später)  │           │  (Entwurf)  │
             └─────────────┘           └─────────────┘           └─────────────┘
                    │                          │
                    ▼                          ▼
             ┌─────────────┐           ┌─────────────┐
             │  Publish to │           │   Queue &   │
             │  Platforms  │           │    Wait     │
             └─────────────┘           └─────────────┘
                    │                          │
                    └──────────┬───────────────┘
                               ▼
                        ┌─────────────┐
                        │  Analytics  │
                        │   Tracking  │
                        └─────────────┘
```

### 3.3 Post-Erstellung (Compose)

#### 3.3.1 Content-Editor

```
┌────────────────────────────────────────────────────────────────┐
│  📝 Neuer Post                                            [X]  │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │ Was möchtest du teilen?                                  │  │
│  │                                                          │  │
│  │ Hier kommt dein Post-Text...                             │  │
│  │                                                          │  │
│  │                                                          │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                    280/280 📊  │
│                                                                │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐                          │
│  │ 📷 Bild │ │ 🎬 Video│ │ #️⃣ Tags │                          │
│  └─────────┘ └─────────┘ └─────────┘                          │
│                                                                │
│  📎 Angehängte Medien:                                        │
│  ┌────────┐ ┌────────┐                                        │
│  │  img1  │ │  img2  │  + Mehr hinzufügen                     │
│  └────────┘ └────────┘                                        │
│                                                                │
├────────────────────────────────────────────────────────────────┤
│  Plattformen:                                                  │
│  ┌────┐ ┌────┐ ┌────┐ ┌────┐ ┌────┐                           │
│  │ 𝕏  │ │ 📷 │ │ 🎵 │ │ 💼 │ │ 📺 │                           │
│  │ ✓  │ │ ✓  │ │ ✓  │ │    │ │    │                           │
│  └────┘ └────┘ └────┘ └────┘ └────┘                           │
│  Twitter Instagram TikTok LinkedIn YouTube                     │
│                                                                │
├────────────────────────────────────────────────────────────────┤
│  ⏰ Zeitpunkt:                                                 │
│  ○ Jetzt posten                                                │
│  ● Planen für: [15.01.2026] [14:30]                           │
│                                                                │
│           [Als Entwurf speichern]  [✓ Planen / Posten]        │
└────────────────────────────────────────────────────────────────┘
```

#### 3.3.2 Plattform-spezifische Anpassungen

Jede Plattform hat unterschiedliche Limits und Formate:

| Plattform | Text-Limit | Bilder | Videos | Besonderheiten |
|-----------|------------|--------|--------|----------------|
| Twitter/X | 280 Zeichen | 4 max | 2:20 min | Threads möglich |
| Instagram | 2.200 Zeichen | 10 max | 60s Feed, 90s Reels | Hashtags wichtig |
| TikTok | 2.200 Zeichen | - | 3-10 min | Nur Video |
| LinkedIn | 3.000 Zeichen | 20 max | 10 min | Professioneller Ton |
| YouTube | 5.000 Zeichen | Thumbnail | Unlimitiert | Title + Description |

**Implementierung:**

```php
class PlatformFormatter {
    public function formatForPlatform(Post $post, string $platform): array {
        return match($platform) {
            'twitter' => $this->formatTwitter($post),
            'instagram' => $this->formatInstagram($post),
            'tiktok' => $this->formatTikTok($post),
            'linkedin' => $this->formatLinkedIn($post),
            'youtube' => $this->formatYouTube($post),
            default => throw new InvalidPlatformException($platform)
        };
    }

    private function formatTwitter(Post $post): array {
        $content = $post->content;

        // Truncate if over 280 chars
        if (mb_strlen($content) > 280) {
            $content = mb_substr($content, 0, 277) . '...';
        }

        return [
            'text' => $content,
            'media_ids' => $this->uploadMediaToTwitter($post->media),
        ];
    }

    // ... weitere Plattformen
}
```

### 3.4 Scheduling-System

#### 3.4.1 Architektur

```
┌─────────────────────────────────────────────────────────────────┐
│                         POST QUEUE                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│   ┌─────────┐      ┌─────────────┐      ┌──────────────┐       │
│   │  User   │─────▶│  API Server │─────▶│   Database   │       │
│   │ creates │      │  validates  │      │ stores post  │       │
│   │  post   │      │  & queues   │      │ status=queue │       │
│   └─────────┘      └─────────────┘      └──────────────┘       │
│                                                  │               │
│                                                  ▼               │
│                                          ┌──────────────┐       │
│   ┌─────────┐      ┌─────────────┐      │    Cron      │       │
│   │Platform │◀─────│   Worker    │◀─────│  (1 min)     │       │
│   │  APIs   │      │  publishes  │      │   triggers   │       │
│   └─────────┘      └─────────────┘      └──────────────┘       │
│        │                   │                                    │
│        ▼                   ▼                                    │
│   ┌─────────┐      ┌─────────────┐                             │
│   │ Success │      │   Update    │                             │
│   │   or    │─────▶│   Status    │                             │
│   │  Error  │      │ & Log Result│                             │
│   └─────────┘      └─────────────┘                             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### 3.4.2 Post Status Lifecycle

```
    ┌─────────┐
    │  draft  │  ← Entwurf, nicht geplant
    └────┬────┘
         │ User plant Post
         ▼
    ┌─────────┐
    │scheduled│  ← Geplant, wartet auf Zeitpunkt
    └────┬────┘
         │ Zeitpunkt erreicht
         ▼
    ┌─────────┐
    │ queued  │  ← In Warteschlange für Publishing
    └────┬────┘
         │ Worker nimmt Post
         ▼
    ┌─────────┐
    │publishing│ ← Wird gerade veröffentlicht
    └────┬────┘
         │
    ┌────┴────┐
    ▼         ▼
┌───────┐ ┌───────┐
│published│ │failed │  ← Erfolgreich oder Fehler
└───────┘ └───────┘
              │ Retry nach X Minuten
              ▼
         ┌─────────┐
         │scheduled│  ← Zurück in Queue (max 3 Retries)
         └─────────┘
```

#### 3.4.3 Worker-Implementierung

**Cron Job (jede Minute):**

```bash
* * * * * php /var/www/postamt/src/worker/publish-scheduled.php
```

**Worker Script:**

```php
<?php
// src/worker/publish-scheduled.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Lib/Database.php';

class ScheduledPostWorker {

    private const MAX_RETRIES = 3;
    private const BATCH_SIZE = 10;

    public function run(): void {
        $posts = $this->getScheduledPosts();

        foreach ($posts as $post) {
            $this->processPost($post);
        }
    }

    private function getScheduledPosts(): array {
        return Database::fetchAll("
            SELECT p.*, pp.id as post_platform_id, pp.account_id, a.platform,
                   a.access_token, a.refresh_token, a.token_expires_at
            FROM posts p
            JOIN post_platforms pp ON p.id = pp.post_id
            JOIN accounts a ON pp.account_id = a.id
            WHERE p.status = 'scheduled'
              AND p.scheduled_at <= datetime('now')
              AND pp.status = 'pending'
              AND (pp.retry_count IS NULL OR pp.retry_count < ?)
            ORDER BY p.scheduled_at ASC
            LIMIT ?
        ", [self::MAX_RETRIES, self::BATCH_SIZE]);
    }

    private function processPost(array $post): void {
        Database::update('post_platforms',
            ['status' => 'publishing'],
            'id = ?',
            [$post['post_platform_id']]
        );

        try {
            $publisher = PlatformPublisherFactory::create($post['platform']);
            $result = $publisher->publish($post);

            Database::update('post_platforms', [
                'status' => 'published',
                'platform_post_id' => $result['id'],
                'published_at' => date('Y-m-d H:i:s'),
            ], 'id = ?', [$post['post_platform_id']]);

            $this->checkAllPlatformsPublished($post['id']);

        } catch (Exception $e) {
            $this->handleFailure($post, $e);
        }
    }

    private function handleFailure(array $post, Exception $e): void {
        $retryCount = ($post['retry_count'] ?? 0) + 1;

        if ($retryCount >= self::MAX_RETRIES) {
            Database::update('post_platforms', [
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'retry_count' => $retryCount,
            ], 'id = ?', [$post['post_platform_id']]);
        } else {
            // Schedule retry in 5 minutes
            Database::update('post_platforms', [
                'status' => 'pending',
                'error_message' => $e->getMessage(),
                'retry_count' => $retryCount,
            ], 'id = ?', [$post['post_platform_id']]);
        }

        // Log error
        error_log("Post publishing failed: " . $e->getMessage());
    }

    private function checkAllPlatformsPublished(int $postId): void {
        $pending = Database::fetch("
            SELECT COUNT(*) as count FROM post_platforms
            WHERE post_id = ? AND status NOT IN ('published', 'failed')
        ", [$postId]);

        if ($pending['count'] === 0) {
            Database::update('posts',
                ['status' => 'published', 'published_at' => date('Y-m-d H:i:s')],
                'id = ?',
                [$postId]
            );
        }
    }
}

// Run worker
$worker = new ScheduledPostWorker();
$worker->run();
```

### 3.5 Kalender-Integration

#### 3.5.1 Kalender-Ansicht Features

- **Monatsansicht**: Übersicht aller geplanten Posts
- **Wochenansicht**: Detaillierte Tagesplanung
- **Drag & Drop**: Posts per Drag & Drop verschieben
- **Farbcodierung**: Jede Plattform hat eigene Farbe
- **Quick-Add**: Klick auf leeren Tag öffnet Compose

#### 3.5.2 Kalender API Endpoints

```
GET  /api/posts/calendar?month=2026-01
     → Alle Posts für Januar 2026

Response:
{
    "posts": [
        {
            "id": 123,
            "content": "Post preview...",
            "scheduled_at": "2026-01-15T14:30:00Z",
            "platforms": ["twitter", "instagram"],
            "status": "scheduled"
        }
    ]
}
```

### 3.6 Media Upload & Storage

#### 3.6.1 Upload Flow

```
┌────────────┐     ┌────────────┐     ┌────────────┐     ┌────────────┐
│   User     │────▶│  Frontend  │────▶│   API      │────▶│  Storage   │
│  selects   │     │  validates │     │  processes │     │  (S3/R2)   │
│   file     │     │  size/type │     │  & uploads │     │            │
└────────────┘     └────────────┘     └────────────┘     └────────────┘
                                             │
                                             ▼
                                      ┌────────────┐
                                      │  Generate  │
                                      │ thumbnails │
                                      │ & variants │
                                      └────────────┘
```

#### 3.6.2 Unterstützte Formate

**Bilder:**
- JPEG, PNG, GIF, WebP
- Max 20 MB pro Bild
- Auto-Resize für Plattform-Anforderungen

**Videos:**
- MP4, MOV, WebM
- Max 500 MB pro Video
- Auto-Transcode wenn nötig

#### 3.6.3 Storage-Struktur

```
/media
  /users
    /{user_id}
      /posts
        /{post_id}
          /original
            - image1.jpg
            - video1.mp4
          /thumbnails
            - image1_thumb.jpg
          /processed
            - video1_twitter.mp4
            - video1_tiktok.mp4
```

---

## 4. Kern-Feature 2: Authentifizierung

### 4.1 Authentifizierungs-Methoden

| Methode | Priorität | Status |
|---------|-----------|--------|
| Email + Passwort | P1 | ✅ Implementiert |
| Google/Gmail OAuth | P1 | 🔄 Zu implementieren |
| Apple Sign-In | P3 | ⏳ Später |

### 4.2 Email + Passwort (Bestehendes System)

#### 4.2.1 Registrierung

```
POST /api/auth/register
{
    "name": "Max Mustermann",
    "email": "max@example.com",
    "password": "sicheresPasswort123"
}
```

**Validierung:**
- Email: Gültiges Format, noch nicht registriert
- Passwort: Minimum 8 Zeichen
- Name: Optional

**Ablauf:**
1. Validiere Input
2. Hash Passwort mit `password_hash()` (bcrypt)
3. Erstelle User in Datenbank
4. Starte Session
5. Redirect zu Dashboard

#### 4.2.2 Login

```
POST /api/auth/login
{
    "email": "max@example.com",
    "password": "sicheresPasswort123"
}
```

**Ablauf:**
1. Finde User per Email
2. Verifiziere Passwort mit `password_verify()`
3. Regeneriere Session ID (Security)
4. Speichere User in Session
5. Redirect zu Dashboard

### 4.3 Google OAuth 2.0 (Neu zu implementieren)

#### 4.3.1 OAuth Flow

```
┌─────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│  User   │────▶│  Postamt    │────▶│   Google    │────▶│  Postamt    │
│ clicks  │     │  redirects  │     │   OAuth     │     │  callback   │
│ "Login  │     │  to Google  │     │   consent   │     │  receives   │
│  with   │     │             │     │   screen    │     │   token     │
│ Google" │     │             │     │             │     │             │
└─────────┘     └─────────────┘     └─────────────┘     └─────────────┘
                                                               │
                                                               ▼
                                                        ┌─────────────┐
                                                        │  Exchange   │
                                                        │  code for   │
                                                        │  tokens     │
                                                        └─────────────┘
                                                               │
                                                               ▼
                                                        ┌─────────────┐
                                                        │ Get user    │
                                                        │ info from   │
                                                        │ Google API  │
                                                        └─────────────┘
                                                               │
                                                               ▼
                                                        ┌─────────────┐
                                                        │ Create or   │
                                                        │ login user  │
                                                        └─────────────┘
```

#### 4.3.2 Google OAuth Implementierung

**1. Google Cloud Console Setup:**
- Neues Projekt erstellen
- OAuth 2.0 Credentials anlegen
- Authorized redirect URI: `https://postamt.ai/auth/google/callback`
- Scopes: `email`, `profile`, `openid`

**2. Config erweitern:**

```php
// src/config.php
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
define('GOOGLE_REDIRECT_URI', BASE_URL . '/auth/google/callback');
```

**3. OAuth Service:**

```php
<?php
// src/Services/GoogleOAuth.php

class GoogleOAuth {

    private const AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const USERINFO_URL = 'https://www.googleapis.com/oauth2/v2/userinfo';

    public function getAuthUrl(): string {
        $params = [
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope' => 'email profile openid',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $this->generateState(),
        ];

        return self::AUTH_URL . '?' . http_build_query($params);
    }

    public function handleCallback(string $code): array {
        // Exchange code for tokens
        $tokens = $this->exchangeCodeForTokens($code);

        // Get user info
        $userInfo = $this->getUserInfo($tokens['access_token']);

        return [
            'google_id' => $userInfo['id'],
            'email' => $userInfo['email'],
            'name' => $userInfo['name'] ?? null,
            'picture' => $userInfo['picture'] ?? null,
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'] ?? null,
        ];
    }

    private function exchangeCodeForTokens(string $code): array {
        $response = HttpClient::post(self::TOKEN_URL, [
            'code' => $code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code',
        ]);

        return json_decode($response, true);
    }

    private function getUserInfo(string $accessToken): array {
        $response = HttpClient::get(self::USERINFO_URL, [], [
            'Authorization: Bearer ' . $accessToken,
        ]);

        return json_decode($response, true);
    }

    private function generateState(): string {
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;
        return $state;
    }

    public function verifyState(string $state): bool {
        return hash_equals($_SESSION['oauth_state'] ?? '', $state);
    }
}
```

**4. Routes hinzufügen:**

```php
// public/index.php

// Google OAuth initiieren
$router->get('/auth/google', function() {
    $oauth = new GoogleOAuth();
    header('Location: ' . $oauth->getAuthUrl());
    exit;
});

// Google OAuth Callback
$router->get('/auth/google/callback', function() {
    $code = $_GET['code'] ?? '';
    $state = $_GET['state'] ?? '';

    $oauth = new GoogleOAuth();

    if (!$oauth->verifyState($state)) {
        header('Location: /login?error=invalid_state');
        exit;
    }

    try {
        $googleUser = $oauth->handleCallback($code);

        // Check if user exists
        $user = Database::fetch(
            'SELECT * FROM users WHERE google_id = ? OR email = ?',
            [$googleUser['google_id'], $googleUser['email']]
        );

        if ($user) {
            // Update Google ID if not set
            if (!$user['google_id']) {
                Database::update('users',
                    ['google_id' => $googleUser['google_id']],
                    'id = ?',
                    [$user['id']]
                );
            }
        } else {
            // Create new user
            $userId = Database::insert('users', [
                'email' => $googleUser['email'],
                'name' => $googleUser['name'],
                'google_id' => $googleUser['google_id'],
                'avatar_url' => $googleUser['picture'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $user = Database::fetch('SELECT * FROM users WHERE id = ?', [$userId]);
        }

        Auth::login($user);
        header('Location: /dashboard');

    } catch (Exception $e) {
        header('Location: /login?error=' . urlencode($e->getMessage()));
    }
    exit;
});
```

**5. Datenbank-Schema erweitern:**

```sql
ALTER TABLE users ADD COLUMN google_id VARCHAR(255) UNIQUE;
ALTER TABLE users ADD COLUMN avatar_url VARCHAR(500);
ALTER TABLE users ALTER COLUMN password_hash DROP NOT NULL;
```

**6. Login-UI anpassen:**

```html
<!-- In login.php -->
<div class="social-login">
    <a href="/auth/google" class="btn btn-google">
        <svg><!-- Google Icon --></svg>
        Mit Google anmelden
    </a>
</div>

<div class="divider">
    <span>oder</span>
</div>

<!-- Bestehendes Email-Formular -->
```

### 4.4 Session-Management

#### 4.4.1 Aktuelles System

```php
// src/Lib/Auth.php (bereits implementiert)

class Auth {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => SESSION_LIFETIME,
                'path' => '/',
                'secure' => APP_ENV === 'production',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    public static function login(array $user): void {
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
        ];
    }

    public static function logout(): void {
        $_SESSION = [];
        session_destroy();
    }
}
```

#### 4.4.2 Security Best Practices

- [x] Session ID Regeneration bei Login
- [x] HTTP-Only Cookies
- [x] Secure Flag in Production
- [x] SameSite Attribute
- [ ] CSRF Token (zu implementieren)
- [ ] Rate Limiting (zu implementieren)

---

## 5. Technische Architektur

### 5.1 Verzeichnisstruktur (Ziel)

```
postamt.ai/
├── public/                     # Web Root
│   ├── index.php              # Entry Point & Routes
│   ├── css/                   # Stylesheets
│   ├── js/                    # Frontend JavaScript
│   ├── views/                 # Page Templates
│   └── uploads/               # Temporäre Uploads
│
├── src/                       # Backend Code
│   ├── config.php            # Configuration
│   ├── Lib/                  # Core Libraries
│   │   ├── Auth.php
│   │   ├── Database.php
│   │   ├── Router.php
│   │   └── HttpClient.php
│   │
│   ├── Services/             # Business Logic (NEU)
│   │   ├── PostService.php
│   │   ├── SchedulerService.php
│   │   ├── MediaService.php
│   │   └── GoogleOAuth.php
│   │
│   ├── Publishers/           # Platform Publishers (NEU)
│   │   ├── PublisherInterface.php
│   │   ├── TwitterPublisher.php
│   │   ├── InstagramPublisher.php
│   │   ├── TikTokPublisher.php
│   │   └── LinkedInPublisher.php
│   │
│   ├── Workers/              # Background Jobs (NEU)
│   │   ├── PublishScheduledPosts.php
│   │   └── FetchAnalytics.php
│   │
│   └── Validators/           # Input Validation (NEU)
│       ├── PostValidator.php
│       └── MediaValidator.php
│
├── data/                      # Data Storage
│   ├── database.sqlite       # SQLite Database
│   └── logs/                 # Application Logs
│
├── storage/                   # File Storage (NEU)
│   └── media/                # Uploaded Media
│
├── tests/                     # Tests (NEU)
│   ├── Unit/
│   └── Integration/
│
├── docs/                      # Documentation
│   └── IMPLEMENTATION_PLAN.md
│
└── docker/                    # Docker Configuration
    ├── Dockerfile
    └── docker-compose.yml
```

### 5.2 Service Layer Pattern

```php
// Beispiel: PostService

class PostService {

    public function create(int $userId, array $data): int {
        // Validierung
        $validator = new PostValidator();
        $validator->validate($data);

        // Media verarbeiten
        $mediaUrls = [];
        if (!empty($data['media'])) {
            $mediaService = new MediaService();
            $mediaUrls = $mediaService->processAndStore($data['media']);
        }

        // Post erstellen
        $postId = Database::insert('posts', [
            'user_id' => $userId,
            'content' => $data['content'],
            'media_urls' => json_encode($mediaUrls),
            'status' => $data['scheduled_at'] ? 'scheduled' : 'draft',
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Plattform-Zuordnungen erstellen
        foreach ($data['platforms'] as $accountId) {
            Database::insert('post_platforms', [
                'post_id' => $postId,
                'account_id' => $accountId,
                'status' => 'pending',
            ]);
        }

        return $postId;
    }

    public function schedule(int $postId, string $scheduledAt): void {
        Database::update('posts', [
            'status' => 'scheduled',
            'scheduled_at' => $scheduledAt,
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$postId]);
    }

    public function publish(int $postId): void {
        // Sofort-Veröffentlichung
        Database::update('posts', [
            'status' => 'queued',
            'scheduled_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$postId]);
    }
}
```

---

## 6. Implementierungsreihenfolge

### Phase 1: Foundation (Woche 1-2) ✅ ABGESCHLOSSEN

#### 1.1 Google OAuth Login ✅
- [x] Google Cloud Console Setup
- [x] OAuth Service implementieren
- [x] Login/Register UI anpassen
- [x] Datenbank-Schema erweitern
- [x] Testing

#### 1.2 CSRF Protection (verschoben)
- [ ] CSRF Token Generation
- [ ] Token Validation Middleware
- [ ] Forms anpassen

### Phase 2: Post Management (Woche 3-4) ✅ ABGESCHLOSSEN

#### 2.1 Post CRUD API ✅
- [x] `POST /api/posts` - Create
- [x] `GET /api/posts` - List
- [x] `GET /api/posts/{id}` - Read
- [x] `PUT /api/posts/{id}` - Update
- [x] `DELETE /api/posts/{id}` - Delete

#### 2.2 Media Upload (verschoben)
- [ ] Upload Endpoint
- [ ] File Validation
- [ ] Storage Integration
- [ ] Thumbnail Generation

#### 2.3 Compose Page Integration ✅
- [x] API-Anbindung
- [x] Live Preview
- [x] Platform Selection
- [x] Scheduling UI

### Phase 3: Social Account Integration (Woche 5-8) 🔄 IN ARBEIT

#### 3.1 Twitter/X Integration ✅
- [x] OAuth 2.0 Flow (mit PKCE)
- [x] Account Connection UI
- [x] Post Publishing Code
- [x] Error Handling
- [ ] **Twitter API Credentials konfigurieren**

#### 3.2 Instagram Integration
- [ ] Meta Business Suite Setup
- [ ] OAuth Flow
- [ ] Image/Video Publishing
- [ ] Stories Support (optional)

#### 3.3 TikTok Integration
- [ ] TikTok for Developers Setup
- [ ] OAuth Flow
- [ ] Video Publishing
- [ ] Draft vs. Direct Publish

#### 3.4 LinkedIn Integration
- [ ] LinkedIn Developer Setup
- [ ] OAuth Flow
- [ ] Post Publishing
- [ ] Company Page Support

### Phase 4: Scheduling Engine (Woche 9-10) 🔄 FAST FERTIG

#### 4.1 Background Worker ✅
- [ ] **Cron Job Setup** (auf Server einrichten)
- [x] Worker Script (`src/Workers/PublishScheduledPosts.php`)
- [x] Retry Logic
- [x] Error Logging
- [x] Lock-File Mechanismus

#### 4.2 Queue Management ✅
- [x] Status Tracking (pending, publishing, published, failed)
- [x] Failure Handling
- [ ] Notifications (optional)

### Phase 5: Calendar & Dashboard (Woche 11-12)

#### 5.1 Calendar Integration
- [ ] Calendar API
- [ ] Drag & Drop
- [ ] Post Preview Popups

#### 5.2 Dashboard Data
- [ ] Real Stats Loading
- [ ] Recent Posts
- [ ] Quick Actions

### Phase 6: Analytics (Woche 13-14)

#### 6.1 Data Collection
- [ ] Analytics Worker
- [ ] Platform API Integration
- [ ] Data Aggregation

#### 6.2 Analytics Dashboard
- [ ] Charts & Graphs
- [ ] Export Functionality

---

## 7. API-Spezifikation

### 7.1 Authentication Endpoints

```yaml
POST /api/auth/register:
  body:
    name: string (optional)
    email: string (required)
    password: string (required, min 8 chars)
  response:
    success: boolean
    user: { id, email, name }

POST /api/auth/login:
  body:
    email: string
    password: string
  response:
    success: boolean
    user: { id, email, name }

POST /api/auth/logout:
  response:
    success: boolean

GET /api/auth/me:
  response:
    user: { id, email, name } | null
```

### 7.2 Post Endpoints

```yaml
POST /api/posts:
  body:
    content: string (required)
    media: File[] (optional)
    platforms: int[] (account IDs)
    scheduled_at: datetime (optional)
  response:
    success: boolean
    post: { id, content, status, scheduled_at }

GET /api/posts:
  query:
    status: draft|scheduled|published|failed
    page: int
    limit: int
  response:
    posts: Post[]
    pagination: { total, page, limit }

GET /api/posts/{id}:
  response:
    post: Post with platforms and analytics

PUT /api/posts/{id}:
  body:
    content: string
    scheduled_at: datetime
  response:
    success: boolean
    post: Post

DELETE /api/posts/{id}:
  response:
    success: boolean

POST /api/posts/{id}/publish:
  response:
    success: boolean
    message: string
```

### 7.3 Account Endpoints

```yaml
GET /api/accounts:
  response:
    accounts: Account[]

POST /api/accounts/connect/{platform}:
  response:
    redirect_url: string

GET /api/accounts/callback/{platform}:
  query:
    code: string
    state: string
  response:
    redirect to /accounts

DELETE /api/accounts/{id}:
  response:
    success: boolean
```

### 7.4 Calendar Endpoints

```yaml
GET /api/calendar:
  query:
    start: date (YYYY-MM-DD)
    end: date (YYYY-MM-DD)
  response:
    posts: [
      {
        id: int,
        content: string (truncated),
        scheduled_at: datetime,
        platforms: string[],
        status: string
      }
    ]

PUT /api/posts/{id}/reschedule:
  body:
    scheduled_at: datetime
  response:
    success: boolean
```

---

## 8. Datenbank-Schema

### 8.1 Erweitertes Schema

```sql
-- Users (erweitert für OAuth)
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255),  -- NULL wenn nur OAuth
    name VARCHAR(255),
    google_id VARCHAR(255) UNIQUE,
    avatar_url VARCHAR(500),
    timezone VARCHAR(50) DEFAULT 'Europe/Berlin',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);

-- Social Media Accounts
CREATE TABLE accounts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    platform VARCHAR(50) NOT NULL,  -- twitter, instagram, tiktok, linkedin, youtube
    platform_user_id VARCHAR(255) NOT NULL,
    platform_username VARCHAR(255),
    display_name VARCHAR(255),
    avatar_url VARCHAR(500),
    access_token TEXT NOT NULL,
    refresh_token TEXT,
    token_expires_at DATETIME,
    scopes TEXT,  -- JSON array of granted scopes
    is_active BOOLEAN DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE(user_id, platform, platform_user_id)
);

-- Posts
CREATE TABLE posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    content TEXT NOT NULL,
    media_urls TEXT,  -- JSON array
    status VARCHAR(20) DEFAULT 'draft',  -- draft, scheduled, queued, publishing, published, failed
    scheduled_at DATETIME,
    published_at DATETIME,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Post Platform Assignments
CREATE TABLE post_platforms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER NOT NULL,
    account_id INTEGER NOT NULL,
    platform_post_id VARCHAR(255),  -- ID from the platform after publishing
    platform_post_url VARCHAR(500),
    status VARCHAR(20) DEFAULT 'pending',  -- pending, publishing, published, failed
    error_message TEXT,
    retry_count INTEGER DEFAULT 0,
    published_at DATETIME,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE
);

-- Analytics
CREATE TABLE analytics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_platform_id INTEGER NOT NULL,
    likes INTEGER DEFAULT 0,
    comments INTEGER DEFAULT 0,
    shares INTEGER DEFAULT 0,
    views INTEGER DEFAULT 0,
    impressions INTEGER DEFAULT 0,
    reach INTEGER DEFAULT 0,
    engagement_rate DECIMAL(5,2),
    fetched_at DATETIME NOT NULL,
    FOREIGN KEY (post_platform_id) REFERENCES post_platforms(id) ON DELETE CASCADE
);

-- Media Files
CREATE TABLE media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    post_id INTEGER,
    original_filename VARCHAR(255),
    stored_filename VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    file_size INTEGER NOT NULL,
    width INTEGER,
    height INTEGER,
    duration INTEGER,  -- für Videos in Sekunden
    storage_path VARCHAR(500) NOT NULL,
    thumbnail_path VARCHAR(500),
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE SET NULL
);

-- Indexes
CREATE INDEX idx_posts_user_status ON posts(user_id, status);
CREATE INDEX idx_posts_scheduled ON posts(scheduled_at) WHERE status = 'scheduled';
CREATE INDEX idx_post_platforms_status ON post_platforms(status);
CREATE INDEX idx_accounts_user_platform ON accounts(user_id, platform);
CREATE INDEX idx_analytics_post_platform ON analytics(post_platform_id);
```

---

## 9. Sicherheitskonzept

### 9.1 Authentication Security

| Maßnahme | Status | Beschreibung |
|----------|--------|--------------|
| Password Hashing | ✅ | bcrypt mit cost factor 10 |
| Session Security | ✅ | HTTPOnly, Secure, SameSite |
| Session Regeneration | ✅ | Bei Login |
| CSRF Protection | 🔄 | Zu implementieren |
| Rate Limiting | 🔄 | Zu implementieren |
| Account Lockout | ⏳ | Nach 5 Fehlversuchen |

### 9.2 API Security

| Maßnahme | Status | Beschreibung |
|----------|--------|--------------|
| Input Validation | 🔄 | Alle Inputs validieren |
| SQL Injection | ✅ | Prepared Statements |
| XSS Prevention | 🔄 | Output Encoding |
| CORS | ⏳ | Für API-Zugriff |
| API Rate Limiting | ⏳ | 100 req/min |

### 9.3 OAuth Token Security

```php
// Tokens verschlüsselt speichern
class TokenEncryption {
    public static function encrypt(string $token): string {
        $key = getenv('TOKEN_ENCRYPTION_KEY');
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $encrypted = sodium_crypto_secretbox($token, $nonce, $key);
        return base64_encode($nonce . $encrypted);
    }

    public static function decrypt(string $encrypted): string {
        $key = getenv('TOKEN_ENCRYPTION_KEY');
        $decoded = base64_decode($encrypted);
        $nonce = substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        return sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
    }
}
```

### 9.4 Media Security

- Dateityp-Validierung (nicht nur Extension)
- Virus-Scan für Uploads (optional)
- Signed URLs für private Medien
- Max Upload Size: 50MB Bilder, 500MB Videos

---

## 10. Testing-Strategie

### 10.1 Unit Tests

```php
// tests/Unit/PostServiceTest.php

class PostServiceTest extends TestCase {

    public function test_create_post_with_valid_data(): void {
        $service = new PostService();
        $postId = $service->create(1, [
            'content' => 'Test post content',
            'platforms' => [1, 2],
            'scheduled_at' => '2026-01-20 14:00:00',
        ]);

        $this->assertIsInt($postId);
        $this->assertGreaterThan(0, $postId);
    }

    public function test_create_post_validates_content(): void {
        $this->expectException(ValidationException::class);

        $service = new PostService();
        $service->create(1, [
            'content' => '', // Empty content
            'platforms' => [1],
        ]);
    }
}
```

### 10.2 Integration Tests

```php
// tests/Integration/TwitterPublisherTest.php

class TwitterPublisherTest extends TestCase {

    public function test_publish_text_post(): void {
        $publisher = new TwitterPublisher();
        $result = $publisher->publish([
            'content' => 'Integration test post ' . time(),
            'access_token' => getenv('TEST_TWITTER_TOKEN'),
        ]);

        $this->assertArrayHasKey('id', $result);

        // Cleanup: Delete test post
        $publisher->delete($result['id']);
    }
}
```

### 10.3 E2E Tests (optional)

- Playwright oder Cypress für Browser-Tests
- Kritische User Flows testen:
  - Register → Login → Create Post → Schedule
  - Connect Account → Publish Post

---

## 11. Deployment & Operations

### 11.1 Environments

| Environment | URL | Database | Purpose |
|-------------|-----|----------|---------|
| Development | localhost:8080 | SQLite | Local development |
| Staging | staging.postamt.ai | PostgreSQL | Testing |
| Production | postamt.ai | PostgreSQL | Live |

### 11.2 CI/CD Pipeline

```yaml
# .github/workflows/deploy.yml

name: Deploy

on:
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Run Tests
        run: composer test

  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to Production
        run: |
          ssh deploy@postamt.ai 'cd /var/www/postamt && git pull && composer install'
```

### 11.3 Cron Jobs (Production)

```bash
# Scheduled Posts publishen (jede Minute)
* * * * * php /var/www/postamt/src/Workers/PublishScheduledPosts.php

# Analytics fetchen (alle 6 Stunden)
0 */6 * * * php /var/www/postamt/src/Workers/FetchAnalytics.php

# Token Refresh (täglich)
0 3 * * * php /var/www/postamt/src/Workers/RefreshExpiredTokens.php
```

### 11.4 Monitoring

- **Error Tracking**: Sentry oder ähnlich
- **Uptime Monitoring**: UptimeRobot
- **Log Management**: Lokale Logs + optional CloudWatch
- **Performance**: New Relic oder Blackfire (optional)

### 11.5 Backup Strategy

```bash
# Tägliches Database Backup
0 2 * * * pg_dump postamt > /backups/postamt_$(date +%Y%m%d).sql

# Media Backup zu S3
0 3 * * * aws s3 sync /var/www/postamt/storage/media s3://postamt-backups/media
```

---

## Zusammenfassung

### Nächste Schritte (Priorität)

1. **Google OAuth implementieren** - Ermöglicht einfaches Onboarding
2. **Post CRUD API bauen** - Grundlage für alles weitere
3. **Twitter Integration** - Erste Plattform zum Testen
4. **Scheduling Worker** - Kern-Feature aktivieren
5. **Instagram Integration** - Zweite wichtige Plattform

### Geschätzter Zeitrahmen

| Phase | Inhalt | Dauer |
|-------|--------|-------|
| Phase 1 | Auth + Foundation | 2 Wochen |
| Phase 2 | Post Management | 2 Wochen |
| Phase 3 | Social Integrations | 4 Wochen |
| Phase 4 | Scheduling Engine | 2 Wochen |
| Phase 5 | Calendar & Dashboard | 2 Wochen |
| Phase 6 | Analytics | 2 Wochen |
| **Total** | **MVP Launch** | **14 Wochen** |

---

*Dieses Dokument wird kontinuierlich aktualisiert während der Entwicklung.*
