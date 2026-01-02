<?php
/**
 * SEO Landing Pages Generator
 * Generates platform, combination, comparison, audience, and guide pages
 * Run: php /var/www/html/src/generate-seo-pages.php
 */

require_once __DIR__ . '/config.php';

$db = new PDO('sqlite:' . DB_PATH);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Generating SEO pages...\n";

// Platform data
$platforms = [
    'instagram' => [
        'name' => 'Instagram',
        'icon' => '&#128247;',
        'features' => ['Reels', 'Stories', 'Posts', 'Karussell'],
        'description' => 'Die beliebteste Plattform fuer visuelle Inhalte'
    ],
    'tiktok' => [
        'name' => 'TikTok',
        'icon' => '&#127925;',
        'features' => ['Videos', 'Sounds', 'Trends', 'Duets'],
        'description' => 'Die am schnellsten wachsende Video-Plattform'
    ],
    'linkedin' => [
        'name' => 'LinkedIn',
        'icon' => '&#128188;',
        'features' => ['Posts', 'Artikel', 'Newsletter', 'Dokumente'],
        'description' => 'Das Business-Netzwerk fuer Professionals'
    ],
    'youtube' => [
        'name' => 'YouTube',
        'icon' => '&#9654;',
        'features' => ['Shorts', 'Videos', 'Community Posts', 'Premieres'],
        'description' => 'Die groesste Video-Plattform der Welt'
    ],
    'twitter' => [
        'name' => 'X/Twitter',
        'icon' => '&#120143;',
        'features' => ['Tweets', 'Threads', 'Spaces', 'Polls'],
        'description' => 'Echtzeit-Kommunikation und Diskussionen'
    ],
    'threads' => [
        'name' => 'Threads',
        'icon' => '&#129525;',
        'features' => ['Posts', 'Replies', 'Reposts'],
        'description' => 'Metas neue Text-basierte Plattform'
    ],
    'bluesky' => [
        'name' => 'Bluesky',
        'icon' => '&#129419;',
        'features' => ['Posts', 'Feeds', 'Lists'],
        'description' => 'Die dezentralisierte Twitter-Alternative'
    ],
];

// Competitors for comparison pages
$competitors = [
    'hootsuite' => ['name' => 'Hootsuite', 'price' => '99 Euro/Monat', 'weakness' => 'komplex und teuer'],
    'buffer' => ['name' => 'Buffer', 'price' => '15 Euro/Monat', 'weakness' => 'begrenzte Features'],
    'later' => ['name' => 'Later', 'price' => '25 Euro/Monat', 'weakness' => 'nur fuer Instagram optimiert'],
    'sprout-social' => ['name' => 'Sprout Social', 'price' => '249 Euro/Monat', 'weakness' => 'Enterprise-Fokus'],
    'planoly' => ['name' => 'Planoly', 'price' => '13 Euro/Monat', 'weakness' => 'limitierte Plattformen'],
];

// Audiences for targeted pages
$audiences = [
    'creator' => ['name' => 'Content Creator', 'description' => 'Fuer YouTuber, Podcaster und Blogger'],
    'influencer' => ['name' => 'Influencer', 'description' => 'Fuer Influencer mit 1K-100K Followern'],
    'kleine-unternehmen' => ['name' => 'Kleine Unternehmen', 'description' => 'Fuer lokale Geschaefte und Startups'],
    'agenturen' => ['name' => 'Agenturen', 'description' => 'Fuer Marketing- und Social-Media-Agenturen'],
    'freelancer' => ['name' => 'Freelancer', 'description' => 'Fuer Selbststaendige und Berater'],
    'solopreneure' => ['name' => 'Solopreneure', 'description' => 'Fuer Einzel-Unternehmer'],
];

// Generate platform pages
echo "Generating platform pages...\n";
foreach ($platforms as $slug => $platform) {
    generatePlatformPage($db, $slug, $platform);
    echo "  - /tools/{$slug}-scheduler\n";
}

// Generate combination pages
echo "\nGenerating combination pages...\n";
$combos = [
    ['instagram', 'tiktok'],
    ['instagram', 'linkedin'],
    ['tiktok', 'youtube'],
    ['instagram', 'twitter'],
    ['linkedin', 'twitter'],
    ['youtube', 'instagram'],
    ['tiktok', 'linkedin'],
    ['instagram', 'threads'],
];

foreach ($combos as $combo) {
    generateCombinationPage($db, $combo[0], $combo[1], $platforms);
    echo "  - /tools/{$combo[0]}-und-{$combo[1]}-gleichzeitig-posten\n";
}

// Generate comparison pages
echo "\nGenerating comparison pages...\n";
foreach ($competitors as $slug => $competitor) {
    generateComparisonPage($db, $slug, $competitor);
    echo "  - /vergleich/{$slug}-alternative\n";
}

// Generate audience pages
echo "\nGenerating audience pages...\n";
foreach ($audiences as $slug => $audience) {
    generateAudiencePage($db, $slug, $audience);
    echo "  - /fuer/{$slug}\n";
}

// Generate guide pages
echo "\nGenerating guide pages...\n";
generateGuidePages($db, $platforms);

echo "\nSEO pages generated successfully!\n";

// Count total pages
$stmt = $db->query('SELECT COUNT(*) as count FROM seo_pages');
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total pages: {$result['count']}\n";

// ============================================
// Generator Functions
// ============================================

function generatePlatformPage($db, $slug, $platform) {
    $features = array_map(function($f) {
        return ['title' => $f, 'icon' => '&#10003;', 'description' => ''];
    }, $platform['features']);

    $faqs = [
        [
            'question' => 'Kann ich mit Postamt auf ' . $platform['name'] . ' posten?',
            'answer' => 'Ja! Postamt unterstuetzt ' . $platform['name'] . ' vollstaendig. Verbinde deinen Account und poste direkt aus unserem Dashboard.'
        ],
        [
            'question' => 'Wie plane ich ' . $platform['name'] . ' Posts im Voraus?',
            'answer' => 'Erstelle deinen Post in Postamt, waehle Datum und Uhrzeit, und wir veroeffentlichen ihn automatisch zur gewuenschten Zeit.'
        ],
        [
            'question' => 'Kann ich gleichzeitig auf ' . $platform['name'] . ' und anderen Plattformen posten?',
            'answer' => 'Absolut! Das ist einer unserer Hauptvorteile. Erstelle einen Post und waehle alle Plattformen aus, auf denen er erscheinen soll.'
        ],
        [
            'question' => 'Was kostet Postamt fuer ' . $platform['name'] . '?',
            'answer' => 'Postamt kostet nur 9 Euro/Monat und beinhaltet alle Plattformen, inklusive ' . $platform['name'] . '. Keine versteckten Kosten.'
        ],
    ];

    $mainContent = generatePlatformContent($platform);

    $data = [
        'slug' => '/tools/' . $slug . '-scheduler',
        'page_type' => 'platform',
        'title' => $platform['name'] . ' Scheduler - Posts planen & automatisch posten | Postamt',
        'meta_description' => $platform['name'] . ' Posts planen und automatisch veroeffentlichen. Mit Postamt sparst du Zeit und postest auf ' . $platform['name'] . ' und anderen Plattformen gleichzeitig. Jetzt testen.',
        'h1' => $platform['name'] . ' Posts planen & automatisch posten',
        'intro_text' => 'Mit Postamt planst du deine ' . $platform['name'] . ' Posts im Voraus und veroeffentlichst sie automatisch zur besten Zeit. Spare 1-2 Stunden taeglich.',
        'main_content' => $mainContent,
        'features_json' => json_encode($features),
        'faqs_json' => json_encode($faqs),
        'related_pages_json' => json_encode([]),
        'primary_keyword' => $slug . ' scheduler',
        'secondary_keywords' => json_encode([$slug . ' posts planen', $slug . ' automatisch posten', $slug . ' scheduling tool']),
    ];

    insertOrUpdatePage($db, $data);
}

function generateCombinationPage($db, $platform1, $platform2, $platforms) {
    $p1 = $platforms[$platform1];
    $p2 = $platforms[$platform2];

    $faqs = [
        [
            'question' => 'Kann ich wirklich auf ' . $p1['name'] . ' und ' . $p2['name'] . ' gleichzeitig posten?',
            'answer' => 'Ja! Erstelle einen Post in Postamt, waehle beide Plattformen aus, und wir veroeffentlichen ihn gleichzeitig. Das Format wird automatisch angepasst.'
        ],
        [
            'question' => 'Werden die Posts automatisch fuer jede Plattform optimiert?',
            'answer' => 'Ja, Postamt passt Zeichenlimits, Hashtags und Bildformate automatisch an die jeweilige Plattform an.'
        ],
        [
            'question' => 'Wie viel Zeit spare ich mit Cross-Posting?',
            'answer' => 'Im Durchschnitt sparen unsere Nutzer 1-2 Stunden pro Tag durch automatisches Cross-Posting.'
        ],
    ];

    $mainContent = generateCombinationContent($p1, $p2);

    $data = [
        'slug' => '/tools/' . $platform1 . '-und-' . $platform2 . '-gleichzeitig-posten',
        'page_type' => 'combination',
        'title' => $p1['name'] . ' und ' . $p2['name'] . ' gleichzeitig posten | Postamt',
        'meta_description' => 'Poste auf ' . $p1['name'] . ' und ' . $p2['name'] . ' gleichzeitig mit einem Klick. Postamt spart dir Zeit beim Cross-Posting. Jetzt testen.',
        'h1' => $p1['name'] . ' und ' . $p2['name'] . ' gleichzeitig posten',
        'intro_text' => 'Schluss mit Copy-Paste zwischen ' . $p1['name'] . ' und ' . $p2['name'] . '. Mit Postamt erstellst du einen Post und veroeffentlichst ihn auf beiden Plattformen gleichzeitig.',
        'main_content' => $mainContent,
        'features_json' => json_encode([]),
        'faqs_json' => json_encode($faqs),
        'related_pages_json' => json_encode([]),
        'primary_keyword' => $platform1 . ' und ' . $platform2 . ' gleichzeitig posten',
        'secondary_keywords' => json_encode(['cross posting', $platform1 . ' ' . $platform2 . ' tool']),
    ];

    insertOrUpdatePage($db, $data);
}

function generateComparisonPage($db, $slug, $competitor) {
    $faqs = [
        [
            'question' => 'Ist Postamt wirklich besser als ' . $competitor['name'] . '?',
            'answer' => 'Das haengt von deinen Beduerfnissen ab. Wenn du Creator oder Solopreneur bist und ein einfaches, guenstiges Tool suchst, ist Postamt die bessere Wahl. ' . $competitor['name'] . ' ist ' . $competitor['weakness'] . '.'
        ],
        [
            'question' => 'Wie viel billiger ist Postamt als ' . $competitor['name'] . '?',
            'answer' => 'Postamt kostet nur 9 Euro/Monat, waehrend ' . $competitor['name'] . ' ' . $competitor['price'] . ' kostet. Du sparst also erheblich.'
        ],
        [
            'question' => 'Kann ich von ' . $competitor['name'] . ' zu Postamt wechseln?',
            'answer' => 'Ja, der Wechsel ist einfach. Du kannst deine Accounts in Minuten verbinden und sofort loslegen.'
        ],
    ];

    $mainContent = generateComparisonContent($competitor);

    $data = [
        'slug' => '/vergleich/' . $slug . '-alternative',
        'page_type' => 'comparison',
        'title' => $competitor['name'] . ' Alternative 2025 - Guenstiger & einfacher | Postamt',
        'meta_description' => 'Suchst du eine ' . $competitor['name'] . ' Alternative? Postamt bietet die gleichen Features fuer 9 Euro/Monat statt ' . $competitor['price'] . '. Jetzt wechseln.',
        'h1' => 'Die beste ' . $competitor['name'] . ' Alternative: Postamt',
        'intro_text' => $competitor['name'] . ' kostet ' . $competitor['price'] . ' und ist oft zu kompliziert. Postamt macht Social Media Management einfach - fuer nur 9 Euro/Monat.',
        'main_content' => $mainContent,
        'features_json' => json_encode([]),
        'faqs_json' => json_encode($faqs),
        'related_pages_json' => json_encode([]),
        'primary_keyword' => $slug . ' alternative',
        'secondary_keywords' => json_encode([$slug . ' vs postamt', $competitor['name'] . ' alternative deutschland']),
    ];

    insertOrUpdatePage($db, $data);
}

function generateAudiencePage($db, $slug, $audience) {
    $faqs = [
        [
            'question' => 'Ist Postamt fuer ' . $audience['name'] . ' geeignet?',
            'answer' => 'Absolut! Postamt wurde speziell fuer ' . $audience['name'] . ' entwickelt. Unser einfaches Interface und faire Preise sind perfekt fuer dich.'
        ],
        [
            'question' => 'Welche Features sind fuer ' . $audience['name'] . ' besonders nuetzlich?',
            'answer' => 'Cross-Posting, Smart Scheduling und unsere Content-Werkzeuge helfen dir, mehr zu erreichen mit weniger Aufwand.'
        ],
    ];

    $mainContent = generateAudienceContent($audience);

    $data = [
        'slug' => '/fuer/' . $slug,
        'page_type' => 'audience',
        'title' => 'Social Media Tool fuer ' . $audience['name'] . ' | Postamt',
        'meta_description' => 'Postamt ist das perfekte Social Media Management Tool fuer ' . $audience['name'] . '. ' . $audience['description'] . '. Jetzt testen.',
        'h1' => 'Social Media Management fuer ' . $audience['name'],
        'intro_text' => $audience['description'] . '. Postamt hilft dir, mehr zu erreichen mit weniger Aufwand.',
        'main_content' => $mainContent,
        'features_json' => json_encode([]),
        'faqs_json' => json_encode($faqs),
        'related_pages_json' => json_encode([]),
        'primary_keyword' => 'social media tool ' . str_replace('-', ' ', $slug),
        'secondary_keywords' => json_encode([]),
    ];

    insertOrUpdatePage($db, $data);
}

function generateGuidePages($db, $platforms) {
    $guides = [
        [
            'slug' => 'beste-zeit-zum-posten',
            'title' => 'Beste Zeit zum Posten auf Social Media 2025',
            'meta' => 'Wann ist die beste Zeit zum Posten auf Instagram, TikTok, LinkedIn und Co? Die besten Posting-Zeiten fuer mehr Reichweite.',
            'h1' => 'Beste Zeit zum Posten auf Social Media',
            'intro' => 'Erfahre wann deine Audience am aktivsten ist und maximiere deine Reichweite durch optimales Timing.',
            'content' => generateBestTimeGuide(),
        ],
        [
            'slug' => 'hashtag-strategie',
            'title' => 'Hashtag Strategie 2025 - Der komplette Guide',
            'meta' => 'Wie du Hashtags richtig nutzt fuer mehr Reichweite auf Instagram, TikTok und Co. Der komplette Hashtag-Guide.',
            'h1' => 'Der ultimative Hashtag Guide fuer Social Media',
            'intro' => 'Lerne wie du Hashtags strategisch einsetzt um mehr Menschen zu erreichen und deine Reichweite zu steigern.',
            'content' => generateHashtagGuide(),
        ],
        [
            'slug' => 'content-kalender-erstellen',
            'title' => 'Content Kalender erstellen - Schritt fuer Schritt',
            'meta' => 'So erstellst du einen Content Kalender der funktioniert. Plane deinen Social Media Content effektiv.',
            'h1' => 'So erstellst du einen Content Kalender',
            'intro' => 'Ein guter Content Kalender ist der Schluessel zu konsistentem Posten. Lerne wie du einen erstellst.',
            'content' => generateContentCalendarGuide(),
        ],
        [
            'slug' => 'social-media-strategie',
            'title' => 'Social Media Strategie 2025 - Komplett-Guide',
            'meta' => 'Entwickle eine Social Media Strategie die funktioniert. Von Zielsetzung bis Umsetzung - alles was du wissen musst.',
            'h1' => 'Social Media Strategie entwickeln',
            'intro' => 'Eine klare Strategie ist die Basis fuer erfolgreichen Social Media Content. Hier lernst du wie.',
            'content' => generateStrategyGuide(),
        ],
    ];

    foreach ($guides as $guide) {
        $data = [
            'slug' => '/guides/' . $guide['slug'],
            'page_type' => 'guide',
            'title' => $guide['title'] . ' | Postamt',
            'meta_description' => $guide['meta'],
            'h1' => $guide['h1'],
            'intro_text' => $guide['intro'],
            'main_content' => $guide['content'],
            'features_json' => json_encode([]),
            'faqs_json' => json_encode([]),
            'related_pages_json' => json_encode([]),
            'primary_keyword' => str_replace('-', ' ', $guide['slug']),
            'secondary_keywords' => json_encode([]),
        ];

        insertOrUpdatePage($db, $data);
        echo "  - /guides/{$guide['slug']}\n";
    }
}

// ============================================
// Content Generator Functions
// ============================================

function generatePlatformContent($platform) {
    $name = $platform['name'];
    $features = implode(', ', $platform['features']);

    return <<<HTML
<h2>Warum {$name} Posts planen?</h2>
<p>Regelmaessiges Posten auf {$name} ist der Schluessel zu mehr Reichweite und Engagement. Aber wer hat schon Zeit, jeden Tag manuell zu posten?</p>
<p>Mit Postamt planst du deine {$name} Posts fuer die ganze Woche in nur 15 Minuten. Waehle die beste Zeit, und Postamt postet automatisch - waehrend du dich auf das konzentrierst was wirklich zaehlt: grossartigen Content erstellen.</p>

<h3>Features fuer {$name}</h3>
<ul>
    <li><strong>Automatisches Posting</strong> - Plane Posts im Voraus und veroeffentliche sie automatisch</li>
    <li><strong>Beste Zeit zum Posten</strong> - Basierend auf deinen eigenen Analytics-Daten</li>
    <li><strong>Multi-Format Support</strong> - {$features}</li>
    <li><strong>Cross-Posting</strong> - Gleichzeitig auf anderen Plattformen posten</li>
    <li><strong>Analytics</strong> - Sieh was funktioniert und optimiere deine Strategie</li>
</ul>

<h3>So planst du {$name} Posts mit Postamt</h3>
<ol>
    <li>Verbinde deinen {$name} Account sicher per OAuth</li>
    <li>Erstelle deinen Post (Text, Bild, Video)</li>
    <li>Waehle Datum und Uhrzeit oder nutze unsere Smart-Scheduling Empfehlung</li>
    <li>Fertig - Postamt uebernimmt den Rest</li>
</ol>

<h3>Warum Postamt statt anderer {$name} Scheduler?</h3>
<p>Die meisten {$name} Scheduler sind entweder zu teuer oder zu kompliziert. Postamt ist anders:</p>
<ul>
    <li><strong>Fair pricing</strong> - Nur 9 Euro/Monat fuer alle Features</li>
    <li><strong>Einfach</strong> - Kein Feature-Bloat, nur das was du brauchst</li>
    <li><strong>Alle Plattformen</strong> - Nicht nur {$name}, sondern alle deine Social Media Accounts</li>
    <li><strong>Made in Germany</strong> - DSGVO-konform und mit deutschem Support</li>
</ul>
HTML;
}

function generateCombinationContent($p1, $p2) {
    return <<<HTML
<h2>Das Problem: Doppelte Arbeit</h2>
<p>Du erstellst einen Post fuer {$p1['name']}. Dann oeffnest du {$p2['name']}, kopierst den Text, passt ihn an, laedst das Bild nochmal hoch... Das dauert ewig und ist frustrierend.</p>
<p>Wenn du auf beiden Plattformen aktiv bist, verbringst du vermutlich 1-2 Stunden taeglich nur mit diesem Copy-Paste-Wahnsinn.</p>

<h2>Die Loesung: Ein Klick, beide Plattformen</h2>
<p>Mit Postamt erstellst du einen Post und waehlst einfach beide Plattformen aus. Postamt passt das Format automatisch an und postet gleichzeitig auf {$p1['name']} und {$p2['name']}.</p>

<h3>Was Postamt automatisch anpasst</h3>
<table class="comparison-table">
    <tr>
        <th>Anpassung</th>
        <th>{$p1['name']}</th>
        <th>{$p2['name']}</th>
    </tr>
    <tr>
        <td>Zeichenlimit</td>
        <td>Optimiert</td>
        <td>Optimiert</td>
    </tr>
    <tr>
        <td>Hashtags</td>
        <td>Plattform-spezifisch</td>
        <td>Plattform-spezifisch</td>
    </tr>
    <tr>
        <td>Bildformat</td>
        <td>Automatisch angepasst</td>
        <td>Automatisch angepasst</td>
    </tr>
    <tr>
        <td>Mentions</td>
        <td>@-Handles uebersetzt</td>
        <td>@-Handles uebersetzt</td>
    </tr>
</table>

<h3>Zeit gespart</h3>
<table class="comparison-table">
    <tr>
        <th>Metrik</th>
        <th>Ohne Postamt</th>
        <th>Mit Postamt</th>
    </tr>
    <tr>
        <td>Zeit pro Post</td>
        <td>15-20 Minuten</td>
        <td class="highlight">2 Minuten</td>
    </tr>
    <tr>
        <td>Workflow</td>
        <td>Manuelles Copy-Paste</td>
        <td class="highlight">Ein Klick</td>
    </tr>
    <tr>
        <td>Fehlerquote</td>
        <td>Hoch (Copy-Paste Fehler)</td>
        <td class="highlight">Minimal</td>
    </tr>
</table>

<h3>Und nicht nur {$p1['name']} und {$p2['name']}</h3>
<p>Mit Postamt kannst du auf allen gaengigen Plattformen gleichzeitig posten: Instagram, TikTok, YouTube, LinkedIn, X/Twitter, Threads und Bluesky. Alles aus einem Dashboard.</p>
HTML;
}

function generateComparisonContent($competitor) {
    return <<<HTML
<h2>Warum eine {$competitor['name']} Alternative suchen?</h2>
<p>{$competitor['name']} ist ein etabliertes Tool, aber es hat einige Nachteile:</p>
<ul>
    <li><strong>Teuer</strong> - {$competitor['price']} fuer Features die du nicht brauchst</li>
    <li><strong>Kompliziert</strong> - Ueberladenes Interface mit zu vielen Optionen</li>
    <li><strong>Enterprise-fokussiert</strong> - Nicht fuer Creator und Solopreneure gemacht</li>
</ul>

<h2>Postamt vs {$competitor['name']}</h2>
<table class="comparison-table">
    <tr>
        <th>Feature</th>
        <th>Postamt</th>
        <th>{$competitor['name']}</th>
    </tr>
    <tr>
        <td>Preis</td>
        <td class="highlight">9 Euro/Monat</td>
        <td>{$competitor['price']}</td>
    </tr>
    <tr>
        <td>Social Accounts</td>
        <td class="check">5 Accounts</td>
        <td>Begrenzt (je nach Plan)</td>
    </tr>
    <tr>
        <td>Alle Plattformen</td>
        <td class="check">&#10003;</td>
        <td>Teilweise</td>
    </tr>
    <tr>
        <td>Einfache UI</td>
        <td class="check">&#10003;</td>
        <td class="cross">Komplex</td>
    </tr>
    <tr>
        <td>Fuer Creator</td>
        <td class="check">&#10003;</td>
        <td class="cross">Enterprise</td>
    </tr>
    <tr>
        <td>Deutsche Support</td>
        <td class="check">&#10003;</td>
        <td class="cross">Meist Englisch</td>
    </tr>
</table>

<h2>Wann {$competitor['name']}, wann Postamt?</h2>
<h3>Waehle {$competitor['name']} wenn:</h3>
<ul>
    <li>Du ein grosses Team (10+ Personen) hast</li>
    <li>Du Enterprise-Features wie Approval Workflows brauchst</li>
    <li>Budget keine Rolle spielt</li>
</ul>

<h3>Waehle Postamt wenn:</h3>
<ul>
    <li>Du Creator, Influencer oder Solopreneur bist</li>
    <li>Du ein einfaches Tool willst das einfach funktioniert</li>
    <li>Du Geld sparen willst ohne auf Features zu verzichten</li>
    <li>Du deutschsprachigen Support schaetzt</li>
</ul>
HTML;
}

function generateAudienceContent($audience) {
    return <<<HTML
<h2>Warum Postamt perfekt fuer {$audience['name']} ist</h2>
<p>{$audience['description']}. Du hast genug zu tun - Social Media Management sollte nicht Stunden deiner Zeit fressen.</p>
<p>Postamt wurde genau fuer Menschen wie dich entwickelt: Einfach, schnell, effektiv. Keine unnoetige Komplexitaet, keine Enterprise-Preise.</p>

<h3>Was {$audience['name']} an Postamt lieben</h3>
<ul>
    <li><strong>Zeitersparnis</strong> - Erstelle Posts fuer alle Plattformen gleichzeitig</li>
    <li><strong>Einfachheit</strong> - Keine Lernkurve, sofort produktiv</li>
    <li><strong>Fairer Preis</strong> - 9 Euro/Monat, keine versteckten Kosten</li>
    <li><strong>Alle Plattformen</strong> - Instagram, TikTok, YouTube, LinkedIn, X und mehr</li>
</ul>

<h3>Typischer Workflow fuer {$audience['name']}</h3>
<ol>
    <li><strong>Montag</strong> - Plane alle Posts fuer die Woche in 30 Minuten</li>
    <li><strong>Unter der Woche</strong> - Postamt veroeffentlicht automatisch</li>
    <li><strong>Freitag</strong> - Checke deine Analytics und optimiere</li>
</ol>
<p>Statt taeglich 1-2 Stunden mit Posten zu verbringen, investierst du 30-60 Minuten pro Woche. Den Rest uebernimmt Postamt.</p>

<h3>Features die {$audience['name']} besonders helfen</h3>
<ul>
    <li><strong>Smart Scheduling</strong> - Wir zeigen dir wann deine Audience online ist</li>
    <li><strong>Content-Werkzeuge</strong> - Hook-Vorschlaege und Hashtag-Recherche</li>
    <li><strong>Cross-Posting</strong> - Ein Post, alle Plattformen</li>
    <li><strong>Analytics</strong> - Verstehe was funktioniert</li>
</ul>
HTML;
}

function generateBestTimeGuide() {
    return <<<HTML
<h2>Warum die Posting-Zeit wichtig ist</h2>
<p>Der Algorithmus jeder Plattform belohnt Engagement in den ersten Minuten nach dem Posten. Wenn du postest, waehrend deine Audience schlaeft, verpasst du dieses kritische Zeitfenster.</p>

<h3>Beste Zeiten nach Plattform</h3>

<h4>Instagram</h4>
<ul>
    <li><strong>Beste Tage:</strong> Dienstag, Mittwoch, Freitag</li>
    <li><strong>Beste Zeiten:</strong> 11:00-13:00 und 19:00-21:00</li>
    <li><strong>Vermeide:</strong> Sonntagmorgen und spaete Nacht</li>
</ul>

<h4>TikTok</h4>
<ul>
    <li><strong>Beste Tage:</strong> Dienstag, Donnerstag, Freitag</li>
    <li><strong>Beste Zeiten:</strong> 12:00-15:00 und 19:00-22:00</li>
    <li><strong>Tipp:</strong> TikTok-Nutzer sind auch spaet aktiv (bis 23:00)</li>
</ul>

<h4>LinkedIn</h4>
<ul>
    <li><strong>Beste Tage:</strong> Dienstag, Mittwoch, Donnerstag</li>
    <li><strong>Beste Zeiten:</strong> 7:00-9:00 und 12:00-14:00</li>
    <li><strong>Vermeide:</strong> Wochenenden (deutlich weniger Aktivitaet)</li>
</ul>

<h4>YouTube</h4>
<ul>
    <li><strong>Beste Tage:</strong> Donnerstag, Freitag</li>
    <li><strong>Beste Zeiten:</strong> 12:00-16:00 und 18:00-21:00</li>
    <li><strong>Tipp:</strong> 2-3 Stunden vor Prime Time hochladen</li>
</ul>

<h3>Wie Postamt dir hilft</h3>
<p>Postamt analysiert wann deine spezifische Audience am aktivsten ist und schlaegt dir die optimalen Zeiten vor. Das ist besser als generische Empfehlungen, weil jede Community anders tickt.</p>
HTML;
}

function generateHashtagGuide() {
    return <<<HTML
<h2>Hashtags richtig nutzen</h2>
<p>Hashtags sind ein maaechtiges Tool um neue Audiences zu erreichen - wenn du sie richtig einsetzt. Zu viele, zu wenige oder die falschen Hashtags koennen deiner Reichweite schaden.</p>

<h3>Hashtag-Strategien nach Plattform</h3>

<h4>Instagram</h4>
<ul>
    <li><strong>Anzahl:</strong> 5-15 Hashtags (nicht mehr!)</li>
    <li><strong>Mix:</strong> Grosse (1M+), mittlere (100K-1M) und kleine (&lt;100K) Hashtags</li>
    <li><strong>Platzierung:</strong> Im Kommentar oder am Ende der Caption</li>
    <li><strong>Tipp:</strong> Rotiere deine Hashtag-Sets um nicht als Spam markiert zu werden</li>
</ul>

<h4>TikTok</h4>
<ul>
    <li><strong>Anzahl:</strong> 3-5 Hashtags</li>
    <li><strong>Fokus:</strong> Trending Hashtags und nischenspezifische Tags</li>
    <li><strong>Tipp:</strong> Nutze FYP (#foryoupage) nur wenn relevant</li>
</ul>

<h4>LinkedIn</h4>
<ul>
    <li><strong>Anzahl:</strong> 3-5 Hashtags</li>
    <li><strong>Art:</strong> Professionell und branchenspezifisch</li>
    <li><strong>Tipp:</strong> LinkedIn bevorzugt wenige, relevante Hashtags</li>
</ul>

<h3>Wie Postamt bei Hashtags hilft</h3>
<p>Postamt bietet Hashtag-Recherche und -Gruppen. Speichere deine besten Hashtag-Sets und fuege sie mit einem Klick zu deinen Posts hinzu.</p>
HTML;
}

function generateContentCalendarGuide() {
    return <<<HTML
<h2>Warum ein Content Kalender?</h2>
<p>Ohne Plan postest du unregelmaessig, vergisst wichtige Termine und verbringst jeden Tag von neuem Zeit mit der Frage "Was poste ich heute?". Ein Content Kalender loest all das.</p>

<h3>Schritt 1: Content-Saeulen definieren</h3>
<p>Ueberlege dir 3-5 Themen-Kategorien fuer deinen Content:</p>
<ul>
    <li><strong>Beispiel fuer Business:</strong> Tipps, Behind-the-Scenes, Kundenstories, Branchen-News, Personal Brand</li>
    <li><strong>Beispiel fuer Creator:</strong> Tutorials, Entertainment, Q&amp;A, Collabs, Daily Life</li>
</ul>

<h3>Schritt 2: Posting-Frequenz festlegen</h3>
<p>Sei realistisch! Besser 3 qualitative Posts pro Woche als 7 durchschnittliche.</p>
<ul>
    <li><strong>Minimum:</strong> 3x pro Woche</li>
    <li><strong>Optimal:</strong> 1x taeglich</li>
    <li><strong>Ambitioniert:</strong> 2-3x taeglich (nur wenn du die Ressourcen hast)</li>
</ul>

<h3>Schritt 3: Wochen-Template erstellen</h3>
<p>Beispiel fuer 5 Posts pro Woche:</p>
<ul>
    <li><strong>Montag:</strong> Motivierender Content (Start in die Woche)</li>
    <li><strong>Dienstag:</strong> Tutorial/Tipp</li>
    <li><strong>Mittwoch:</strong> Behind-the-Scenes</li>
    <li><strong>Donnerstag:</strong> Community/Q&amp;A</li>
    <li><strong>Freitag:</strong> Entertainment/Leichterer Content</li>
</ul>

<h3>Mit Postamt umsetzen</h3>
<p>In Postamt siehst du deinen Content Kalender visuell und kannst Posts per Drag &amp; Drop verschieben. So behaeltst du immer den Ueberblick.</p>
HTML;
}

function generateStrategyGuide() {
    return <<<HTML
<h2>Was ist eine Social Media Strategie?</h2>
<p>Eine Strategie ist dein Fahrplan: Wer ist deine Zielgruppe? Was willst du erreichen? Welchen Content erstellst du? Ohne Strategie postest du ins Blaue.</p>

<h3>Die 5 Saeulen einer guten Strategie</h3>

<h4>1. Ziele definieren</h4>
<p>Was willst du mit Social Media erreichen?</p>
<ul>
    <li>Markenbekanntheit aufbauen</li>
    <li>Leads generieren</li>
    <li>Verkauefe steigern</li>
    <li>Community aufbauen</li>
    <li>Als Experte positionieren</li>
</ul>

<h4>2. Zielgruppe verstehen</h4>
<p>Wer sind deine idealen Follower?</p>
<ul>
    <li>Demografik (Alter, Ort, Beruf)</li>
    <li>Interessen und Probleme</li>
    <li>Welche Plattformen nutzen sie?</li>
    <li>Wann sind sie online?</li>
</ul>

<h4>3. Plattformen waehlen</h4>
<p>Du musst nicht ueberall sein. Waehle 2-3 Plattformen wo deine Zielgruppe aktiv ist.</p>

<h4>4. Content-Strategie</h4>
<p>Welche Art von Content erstellst du?</p>
<ul>
    <li>Educational (Tutorials, Tipps)</li>
    <li>Entertaining (Humor, Storytelling)</li>
    <li>Inspirational (Motivation, Erfolgsgeschichten)</li>
    <li>Promotional (Produkte, Services)</li>
</ul>

<h4>5. Messen und Optimieren</h4>
<p>Tracke deine wichtigsten Metriken und passe deine Strategie basierend auf Daten an.</p>

<h3>Wie Postamt deine Strategie unterstuetzt</h3>
<p>Mit Postamt hast du Analytics, Scheduling und Content-Werkzeuge an einem Ort. So setzt du deine Strategie effizient um.</p>
HTML;
}

// ============================================
// Database Helper
// ============================================

function insertOrUpdatePage($db, $data) {
    $stmt = $db->prepare('
        INSERT INTO seo_pages (slug, page_type, title, meta_description, h1, intro_text, main_content, features_json, faqs_json, related_pages_json, primary_keyword, secondary_keywords, updated_at)
        VALUES (:slug, :page_type, :title, :meta_description, :h1, :intro_text, :main_content, :features_json, :faqs_json, :related_pages_json, :primary_keyword, :secondary_keywords, datetime("now"))
        ON CONFLICT(slug) DO UPDATE SET
            page_type = :page_type,
            title = :title,
            meta_description = :meta_description,
            h1 = :h1,
            intro_text = :intro_text,
            main_content = :main_content,
            features_json = :features_json,
            faqs_json = :faqs_json,
            related_pages_json = :related_pages_json,
            primary_keyword = :primary_keyword,
            secondary_keywords = :secondary_keywords,
            updated_at = datetime("now")
    ');
    $stmt->execute($data);
}
