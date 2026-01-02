<?php
/**
 * SEO Landing Page Template
 * Variables expected: $page (array with page data)
 */

$features = json_decode($page['features_json'] ?? '[]', true) ?: [];
$faqs = json_decode($page['faqs_json'] ?? '[]', true) ?: [];
$relatedPages = json_decode($page['related_pages_json'] ?? '[]', true) ?: [];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page['meta_description']) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://postamt.ai<?= htmlspecialchars($page['slug']) ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($page['title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page['meta_description']) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://postamt.ai<?= htmlspecialchars($page['slug']) ?>">
    <meta property="og:site_name" content="Postamt">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page['title']) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page['meta_description']) ?>">

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

    <?php if (!empty($faqs)): ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            <?php foreach ($faqs as $i => $faq): ?>
            {
                "@type": "Question",
                "name": <?= json_encode($faq['question']) ?>,
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": <?= json_encode($faq['answer']) ?>
                }
            }<?= $i < count($faqs) - 1 ? ',' : '' ?>
            <?php endforeach; ?>
        ]
    }
    </script>
    <?php endif; ?>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #fff;
            color: #111;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 24px;
        }

        nav {
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .logo {
            font-size: 20px;
            font-weight: 700;
            color: #111;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .nav-links a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-links a:hover {
            color: #111;
        }

        .hero {
            padding: 60px 0 40px;
            text-align: center;
        }

        .breadcrumb {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #999;
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #111;
        }

        h1 {
            font-size: clamp(28px, 5vw, 42px);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
            color: #111;
        }

        .hero p.intro {
            font-size: 18px;
            color: #666;
            max-width: 600px;
            margin: 0 auto 32px;
        }

        .btn-primary {
            display: inline-block;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 500;
            border: none;
            border-radius: 6px;
            background: #111;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #333;
        }

        .btn-secondary {
            display: inline-block;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 500;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fff;
            color: #111;
            cursor: pointer;
            text-decoration: none;
            transition: border-color 0.2s;
        }

        .btn-secondary:hover {
            border-color: #999;
        }

        .cta-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        section {
            padding: 60px 0;
        }

        section h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #111;
        }

        .content-section {
            background: #fafafa;
            border-radius: 12px;
            padding: 40px;
        }

        .content-section h2 {
            font-size: 20px;
            margin-bottom: 16px;
        }

        .content-section h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 28px 0 12px;
            color: #111;
        }

        .content-section p {
            color: #444;
            margin-bottom: 14px;
            font-size: 15px;
            line-height: 1.7;
        }

        .content-section ul, .content-section ol {
            margin: 14px 0;
            padding-left: 24px;
            color: #444;
        }

        .content-section li {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .content-section strong {
            color: #111;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 32px;
        }

        .feature-card {
            background: #fafafa;
            border-radius: 10px;
            padding: 28px;
            transition: background 0.2s;
        }

        .feature-card:hover {
            background: #f5f5f5;
        }

        .feature-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #111;
        }

        .feature-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #fff;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            font-size: 20px;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin: 28px 0;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .comparison-table th,
        .comparison-table td {
            padding: 14px 18px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .comparison-table th {
            background: #fafafa;
            font-weight: 600;
            color: #111;
        }

        .comparison-table td {
            color: #444;
        }

        .comparison-table tr:last-child td {
            border-bottom: none;
        }

        .comparison-table .check {
            color: #22c55e;
        }

        .comparison-table .cross {
            color: #ef4444;
        }

        .comparison-table .highlight {
            color: #111;
            font-weight: 600;
        }

        .faq-section h2 {
            text-align: center;
            margin-bottom: 32px;
        }

        .faq-list {
            max-width: 700px;
            margin: 0 auto;
        }

        .faq-item {
            background: #fafafa;
            border-radius: 8px;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .faq-question {
            padding: 18px 22px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
            font-size: 15px;
            transition: background 0.2s;
        }

        .faq-question:hover {
            background: #f5f5f5;
        }

        .faq-question::after {
            content: '+';
            font-size: 20px;
            color: #999;
            transition: transform 0.2s;
        }

        .faq-item.open .faq-question::after {
            transform: rotate(45deg);
        }

        .faq-answer {
            padding: 0 22px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            color: #666;
            line-height: 1.6;
            font-size: 14px;
        }

        .faq-item.open .faq-answer {
            padding: 0 22px 18px;
            max-height: 500px;
        }

        .related-section h2 {
            text-align: center;
            margin-bottom: 28px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        .related-link {
            display: block;
            background: #fafafa;
            border-radius: 8px;
            padding: 20px;
            text-decoration: none;
            color: #111;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .related-link:hover {
            background: #f5f5f5;
        }

        .related-link span {
            display: block;
            font-size: 12px;
            color: #999;
            margin-top: 6px;
            font-weight: 400;
        }

        .final-cta {
            text-align: center;
            padding: 80px 0;
        }

        .final-cta h2 {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .final-cta p {
            color: #666;
            font-size: 16px;
            margin-bottom: 28px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        footer {
            padding: 40px 0;
            border-top: 1px solid #eee;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-links {
            display: flex;
            gap: 24px;
        }

        .footer-links a {
            color: #999;
            text-decoration: none;
            font-size: 13px;
        }

        .footer-links a:hover {
            color: #666;
        }

        .footer-copy {
            color: #999;
            font-size: 13px;
        }

        /* How it works steps */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin: 32px 0;
        }

        .step {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            background: #fafafa;
            border-radius: 10px;
            padding: 24px;
        }

        .step-number {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #111;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .step-content h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .step-content p {
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            h1 {
                font-size: 24px;
            }

            .hero p.intro {
                font-size: 15px;
            }

            section {
                padding: 40px 0;
            }

            .content-section {
                padding: 24px;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
                text-align: center;
            }

            .comparison-table {
                font-size: 13px;
            }

            .comparison-table th,
            .comparison-table td {
                padding: 10px;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .step {
                flex-direction: column;
                text-align: center;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <a href="/" class="logo">Postamt</a>
            <div class="nav-links">
                <a href="/#features">Features</a>
                <a href="/#pricing">Preise</a>
                <a href="/login">Login</a>
            </div>
        </nav>

        <section class="hero">
            <nav class="breadcrumb">
                <a href="/">Home</a>
                <span>/</span>
                <?php if ($page['page_type'] === 'platform' || $page['page_type'] === 'combination'): ?>
                <a href="/tools">Tools</a>
                <?php elseif ($page['page_type'] === 'comparison'): ?>
                <a href="/vergleich">Vergleich</a>
                <?php elseif ($page['page_type'] === 'audience'): ?>
                <a href="/fuer">Zielgruppen</a>
                <?php elseif ($page['page_type'] === 'guide'): ?>
                <a href="/guides">Guides</a>
                <?php endif; ?>
                <span>/</span>
                <span><?= htmlspecialchars($page['h1']) ?></span>
            </nav>

            <h1><?= htmlspecialchars($page['h1']) ?></h1>

            <p class="intro"><?= htmlspecialchars($page['intro_text']) ?></p>

            <div class="cta-buttons">
                <a href="/register" class="btn-primary">Kostenlos testen</a>
                <a href="/#features" class="btn-secondary">Mehr erfahren</a>
            </div>
        </section>

        <section>
            <div class="content-section">
                <?= $page['main_content'] ?>
            </div>
        </section>

        <?php if (!empty($features)): ?>
        <section>
            <h2 style="text-align: center;">Features fuer dich</h2>
            <div class="features-grid">
                <?php foreach ($features as $feature): ?>
                <div class="feature-card">
                    <div class="feature-icon"><?= $feature['icon'] ?? '&#10003;' ?></div>
                    <h3><?= htmlspecialchars($feature['title'] ?? $feature) ?></h3>
                    <?php if (isset($feature['description'])): ?>
                    <p><?= htmlspecialchars($feature['description']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <section>
            <h2 style="text-align: center;">So funktioniert's</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Account verbinden</h3>
                        <p>Verbinde deine Social Media Accounts mit wenigen Klicks. OAuth macht es sicher und einfach.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Post erstellen</h3>
                        <p>Schreibe deinen Post einmal. Fuege Bilder oder Videos hinzu. Nutze unsere Hook-Vorschlaege.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Plattformen waehlen</h3>
                        <p>Waehle auf welchen Plattformen dein Post erscheinen soll. Wir passen das Format automatisch an.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Veroeffentlichen oder planen</h3>
                        <p>Poste sofort oder plane fuer die beste Zeit. Unsere Analytics zeigen dir wann deine Audience online ist.</p>
                    </div>
                </div>
            </div>
        </section>

        <?php if (!empty($faqs)): ?>
        <section class="faq-section">
            <h2>Haeufige Fragen</h2>
            <div class="faq-list">
                <?php foreach ($faqs as $faq): ?>
                <div class="faq-item">
                    <div class="faq-question"><?= htmlspecialchars($faq['question']) ?></div>
                    <div class="faq-answer"><?= htmlspecialchars($faq['answer']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($relatedPages)): ?>
        <section class="related-section">
            <h2>Das koennte dich auch interessieren</h2>
            <div class="related-grid">
                <?php foreach ($relatedPages as $related): ?>
                <a href="<?= htmlspecialchars($related['slug']) ?>" class="related-link">
                    <?= htmlspecialchars($related['title']) ?>
                    <span><?= htmlspecialchars($related['description'] ?? '') ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <section class="final-cta">
            <h2>Bereit loszulegen?</h2>
            <p>Spare Zeit beim Social Media Management. Teste Postamt kostenlos.</p>
            <a href="/register" class="btn-primary">Jetzt kostenlos starten</a>
        </section>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="/">Home</a>
                    <a href="/#features">Features</a>
                    <a href="/#pricing">Preise</a>
                    <a href="/login">Login</a>
                </div>
                <div class="footer-copy">
                    Gebaut mit Leidenschaft fuer Creator
                </div>
            </div>
        </div>
    </footer>

    <script>
        // FAQ accordion
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const item = question.parentElement;
                const wasOpen = item.classList.contains('open');

                // Close all
                document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));

                // Open clicked if it wasn't open
                if (!wasOpen) {
                    item.classList.add('open');
                }
            });
        });
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
