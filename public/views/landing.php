<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postamt.ai – Social Media Management. Einfach.</title>
    <meta name="description" content="Poste auf Instagram, TikTok, YouTube, LinkedIn, X, Bluesky, Snapchat, WhatsApp und Telegram gleichzeitig. Plane deine Social Media Woche in 15 Minuten. Fuer Creator die es ernst meinen.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://postamt.ai/">

    <!-- Open Graph -->
    <meta property="og:title" content="Postamt.ai – Social Media Management. Einfach.">
    <meta property="og:description" content="Poste auf Instagram, TikTok, YouTube, LinkedIn, X, Bluesky, Snapchat, WhatsApp und Telegram gleichzeitig. Plane deine Social Media Woche in 15 Minuten.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://postamt.ai/">
    <meta property="og:site_name" content="Postamt">
    <meta property="og:locale" content="de_DE">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Postamt.ai  – Social Media Management. Einfach.">
    <meta name="twitter:description" content="Poste auf Instagram, TikTok, YouTube, LinkedIn, X, Bluesky, Snapchat, WhatsApp und Telegram gleichzeitig. Plane deine Social Media Woche in 15 Minuten.">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Navigation */
        nav {
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        /* Hero */
        .hero {
            padding: 80px 0 60px;
            text-align: center;
        }

        .badge {
            display: inline-block;
            background: #f5f5f5;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            color: #666;
            margin-bottom: 24px;
        }

        h1 {
            font-size: 48px;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .hero p {
            font-size: 18px;
            color: #666;
            max-width: 520px;
            margin: 0 auto 32px;
        }

        /* CTA */
        .cta-box {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .email-input {
            padding: 14px 18px;
            font-size: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            color: #111;
            width: 280px;
            outline: none;
        }

        .email-input:focus {
            border-color: #111;
        }

        .email-input::placeholder {
            color: #999;
        }

        .btn-primary {
            padding: 14px 24px;
            font-size: 15px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            background: #111;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #333;
        }

        .btn-primary:disabled {
            background: #999;
            cursor: not-allowed;
        }

        .social-proof {
            color: #999;
            font-size: 13px;
        }

        /* Platforms */
        .platforms {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-top: 48px;
            padding: 32px;
            background: #fafafa;
            border-radius: 12px;
        }

        .platform {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 12px;
        }

        .platform-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            position: relative;
            transition: transform 0.2s ease;
        }

        .platform:hover .platform-icon {
            transform: scale(1.08);
        }

        .platform-icon::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 14px;
            filter: blur(12px);
            opacity: 0.5;
            z-index: 0;
        }

        .platform-icon svg {
            width: 26px;
            height: 26px;
            position: relative;
            z-index: 1;
        }

        /* Instagram - gradient */
        .platform.instagram .platform-icon {
            background: linear-gradient(135deg, #833ab4, #fd1d1d, #fcb045);
        }
        .platform.instagram .platform-icon::before {
            background: linear-gradient(135deg, #833ab4, #fd1d1d, #fcb045);
        }
        .platform.instagram .platform-icon svg {
            fill: #fff;
        }

        /* TikTok - black with accent */
        .platform.tiktok .platform-icon {
            background: #000;
            box-shadow: -2px 0 0 #00f2ea, 2px 0 0 #ff0050;
        }
        .platform.tiktok .platform-icon::before {
            background: linear-gradient(135deg, #00f2ea, #ff0050);
        }
        .platform.tiktok .platform-icon svg {
            fill: #fff;
        }

        /* YouTube - red */
        .platform.youtube .platform-icon {
            background: #FF0000;
        }
        .platform.youtube .platform-icon::before {
            background: #FF0000;
        }
        .platform.youtube .platform-icon svg {
            fill: #fff;
        }

        /* LinkedIn - blue */
        .platform.linkedin .platform-icon {
            background: #0A66C2;
        }
        .platform.linkedin .platform-icon::before {
            background: #0A66C2;
        }
        .platform.linkedin .platform-icon svg {
            fill: #fff;
        }

        /* X - black */
        .platform.x .platform-icon {
            background: #000;
        }
        .platform.x .platform-icon::before {
            background: #000;
            opacity: 0.3;
        }
        .platform.x .platform-icon svg {
            fill: #fff;
        }

        /* Bluesky - blue */
        .platform.bluesky .platform-icon {
            background: #0085ff;
        }
        .platform.bluesky .platform-icon::before {
            background: #0085ff;
        }
        .platform.bluesky .platform-icon svg {
            fill: #fff;
        }

        /* Snapchat - yellow */
        .platform.snapchat .platform-icon {
            background: #FFFC00;
        }
        .platform.snapchat .platform-icon::before {
            background: #FFFC00;
        }
        .platform.snapchat .platform-icon svg {
            fill: #000;
        }

        /* WhatsApp - green */
        .platform.whatsapp .platform-icon {
            background: #25D366;
        }
        .platform.whatsapp .platform-icon::before {
            background: #25D366;
        }
        .platform.whatsapp .platform-icon svg {
            fill: #fff;
        }

        /* Telegram - blue */
        .platform.telegram .platform-icon {
            background: #0088cc;
        }
        .platform.telegram .platform-icon::before {
            background: #0088cc;
        }
        .platform.telegram .platform-icon svg {
            fill: #fff;
        }

        /* Butterfly fly-in animation */
        .platform {
            opacity: 0;
        }

        .platform.flying {
            position: fixed;
            z-index: 1000;
            pointer-events: none;
            opacity: 1;
        }

        .platform.flying .platform-icon {
            animation: flutter 0.3s ease-in-out infinite alternate;
        }

        @keyframes flutter {
            0% { transform: rotate(-8deg) scale(1.1); }
            100% { transform: rotate(8deg) scale(1.15); }
        }

        .platform.landed {
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .platform.landed .platform-icon {
            animation: landBounce 0.4s ease-out;
        }

        @keyframes landBounce {
            0% { transform: scale(1.2); }
            50% { transform: scale(0.9); }
            75% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Section */
        section {
            padding: 80px 0;
        }

        section.bordered {
            border-top: 1px solid #eee;
        }

        .section-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #999;
            margin-bottom: 12px;
        }

        section h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -0.01em;
        }

        section > p {
            color: #666;
            max-width: 520px;
            margin-bottom: 48px;
        }

        /* Features Grid */
        .features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 32px;
        }

        .feature {
            padding: 24px;
            background: #fafafa;
            border-radius: 12px;
        }

        .feature-number {
            font-size: 12px;
            font-weight: 600;
            color: #999;
            margin-bottom: 12px;
        }

        .feature h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .feature p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        /* How it works */
        .steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .step {
            text-align: center;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #111;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin: 0 auto 16px;
        }

        .step h3 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .step p {
            font-size: 13px;
            color: #666;
        }

        /* Pricing */
        .pricing-card {
            max-width: 400px;
            margin: 0 auto;
            padding: 32px;
            border: 1px solid #eee;
            border-radius: 12px;
            text-align: center;
        }

        .pricing-badge {
            display: inline-block;
            background: #111;
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 16px;
        }

        .price {
            margin: 20px 0;
        }

        .price .original {
            text-decoration: line-through;
            color: #999;
            font-size: 18px;
            margin-right: 8px;
        }

        .price .amount {
            font-size: 48px;
            font-weight: 700;
        }

        .price .period {
            color: #666;
        }

        .pricing-features {
            text-align: left;
            margin: 24px 0;
            list-style: none;
        }

        .pricing-features li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #444;
        }

        .pricing-features li:last-child {
            border-bottom: none;
        }

        .pricing-features li::before {
            content: "\2713";
            color: #111;
            margin-right: 10px;
        }

        .pricing-card .btn-primary {
            width: 100%;
            padding: 16px;
        }

        /* Final CTA */
        .final-cta {
            text-align: center;
            background: #fafafa;
            border-radius: 16px;
            padding: 60px 24px;
            margin: 0 24px;
        }

        .final-cta h2 {
            font-size: 28px;
            margin-bottom: 12px;
        }

        .final-cta p {
            color: #666;
            margin-bottom: 24px;
        }

        /* Footer */
        footer {
            padding: 32px 0;
            border-top: 1px solid #eee;
            text-align: center;
            color: #999;
            font-size: 13px;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 14px;
            z-index: 1000;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .toast.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            h1 {
                font-size: 32px;
            }

            .hero p {
                font-size: 16px;
            }

            .email-input {
                width: 100%;
            }

            .cta-box {
                flex-direction: column;
                padding: 0 20px;
            }

            .btn-primary {
                width: 100%;
            }

            .platforms {
                flex-wrap: wrap;
                gap: 16px;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .steps {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }

            section {
                padding: 60px 0;
            }

            .final-cta {
                margin: 0;
                border-radius: 0;
            }
        }

        @media (max-width: 480px) {
            .steps {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 28px;
            }

            section h2 {
                font-size: 24px;
            }
        }
    </style>

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Postamt",
        "description": "Social Media Management Tool - Poste auf Instagram, TikTok, YouTube, LinkedIn, X, Bluesky, Snapchat, WhatsApp und Telegram gleichzeitig.",
        "url": "https://postamt.ai",
        "applicationCategory": "BusinessApplication",
        "operatingSystem": "Web",
        "offers": {
            "@type": "Offer",
            "price": "9",
            "priceCurrency": "EUR",
            "priceValidUntil": "2026-12-31"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "5",
            "ratingCount": "1"
        },
        "featureList": [
            "Cross-Posting auf allen Plattformen",
            "Smart Scheduling",
            "Content-Werkzeuge",
            "Analytics Dashboard"
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Postamt",
        "url": "https://postamt.ai",
        "description": "Social Media Management fuer Creator"
    }
    </script>
</head>
<body>
    <nav>
        <div class="container">
            <a href="/" class="logo">Postamt.ai</a>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#pricing">Preise</a>
                <a href="/login">Login</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <section class="hero">
            <div class="badge">Beta – Erste 100 User: Lifetime Deal</div>

            <h1>Social Media Management.<br>Einfach.</h1>

            <p>Poste auf allen Plattformen gleichzeitig. Plane deine Woche in 15 Minuten. Fuer Creator die es ernst meinen.</p>

            <div class="cta-box">
                <input type="email" class="email-input" placeholder="deine@email.de" id="waitlist-email">
                <button class="btn-primary" id="waitlist-btn" onclick="joinWaitlist()">Warteliste beitreten</button>
            </div>

            <p class="social-proof" id="waitlist-count">Lade...</p>

            <div class="platforms">
                <div class="platform instagram">
                    <div class="platform-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </div>
                    <span>Instagram</span>
                </div>
                <div class="platform tiktok">
                    <div class="platform-icon">
                        <svg viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                    </div>
                    <span>TikTok</span>
                </div>
                <div class="platform youtube">
                    <div class="platform-icon">
                        <svg viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </div>
                    <span>YouTube</span>
                </div>
                <div class="platform linkedin">
                    <div class="platform-icon">
                        <svg viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </div>
                    <span>LinkedIn</span>
                </div>
                <div class="platform x">
                    <div class="platform-icon">
                        <svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </div>
                    <span>X</span>
                </div>
                <div class="platform bluesky">
                    <div class="platform-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 10.8c-1.087-2.114-4.046-6.053-6.798-7.995C2.566.944 1.561 1.266.902 1.565.139 1.908 0 3.08 0 3.768c0 .69.378 5.65.624 6.479.815 2.736 3.713 3.66 6.383 3.364.136-.02.275-.039.415-.056-.138.022-.276.04-.415.056-3.912.58-7.387 2.005-2.83 7.078 5.013 5.19 6.87-1.113 7.823-4.308.953 3.195 2.05 9.271 7.733 4.308 4.267-4.308 1.172-6.498-2.74-7.078a8.741 8.741 0 0 1-.415-.056c.14.017.279.036.415.056 2.67.297 5.568-.628 6.383-3.364.246-.828.624-5.79.624-6.478 0-.69-.139-1.861-.902-2.206-.659-.298-1.664-.62-4.3 1.24C16.046 4.748 13.087 8.687 12 10.8z"/></svg>
                    </div>
                    <span>Bluesky</span>
                </div>
                <div class="platform snapchat">
                    <div class="platform-icon">
                        <svg viewBox="0 0 24 24"><path d="M12.166 0c-1.903.006-3.612.736-4.885 1.97C5.932 3.307 5.322 5.214 5.322 7.2v.822c-.042.006-.09.006-.132.006-.558 0-1.122.222-1.476.624a1.64 1.64 0 0 0-.348 1.404c.162.702.768 1.146 1.476 1.248.006.054.018.108.024.162.006.048.012.102.012.156.036.366.102.738.186 1.104.018.066.036.132.054.198a8.21 8.21 0 0 0 1.02 2.328c.678 1.068 1.614 1.944 2.76 2.514.102.048.198.102.306.15.174.09.354.168.54.24l.006.012c-.012.396-.078.792-.24 1.152a.943.943 0 0 1-.186.27c-.312.33-.81.486-1.296.618a8.89 8.89 0 0 0-.492.132 3.391 3.391 0 0 0-.708.288c-.456.258-.78.642-.876 1.134-.06.312-.024.636.108.936.264.588.816.996 1.44 1.218.624.228 1.308.306 1.986.306.462 0 .924-.036 1.362-.078.27-.024.534-.054.798-.054.312 0 .606.036.906.126.39.114.756.312 1.134.522.516.282 1.062.588 1.698.798a5.63 5.63 0 0 0 1.71.27c.594 0 1.176-.09 1.71-.27.636-.21 1.182-.516 1.698-.798.378-.21.744-.408 1.134-.522.3-.09.594-.126.906-.126.264 0 .528.03.798.054.438.042.9.078 1.362.078.678 0 1.362-.078 1.986-.306.624-.222 1.176-.63 1.44-1.218a1.54 1.54 0 0 0 .108-.936c-.096-.492-.42-.876-.876-1.134a3.391 3.391 0 0 0-.708-.288 8.89 8.89 0 0 0-.492-.132c-.486-.132-.984-.288-1.296-.618a.943.943 0 0 1-.186-.27c-.162-.36-.228-.756-.24-1.152l.006-.012c.186-.072.366-.15.54-.24.108-.048.204-.102.306-.15 1.146-.57 2.082-1.446 2.76-2.514a8.21 8.21 0 0 0 1.02-2.328c.018-.066.036-.132.054-.198.084-.366.15-.738.186-1.104 0-.054.006-.108.012-.156.006-.054.018-.108.024-.162.708-.102 1.314-.546 1.476-1.248a1.64 1.64 0 0 0-.348-1.404c-.354-.402-.918-.624-1.476-.624-.042 0-.09 0-.132.006V7.2c0-1.986-.61-3.893-1.959-5.23C15.778.736 14.069.006 12.166 0z"/></svg>
                    </div>
                    <span>Snapchat</span>
                </div>
                <div class="platform whatsapp">
                    <div class="platform-icon">
                        <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <span>WhatsApp</span>
                </div>
                <div class="platform telegram">
                    <div class="platform-icon">
                        <svg viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                    </div>
                    <span>Telegram</span>
                </div>
            </div>
        </section>

        <section class="bordered" id="features">
            <p class="section-label">Features</p>
            <h2>Alles was du brauchst</h2>
            <p>Kein Feature-Bloat. Nur das was wirklich hilft.</p>

            <div class="features">
                <div class="feature">
                    <p class="feature-number">01</p>
                    <h3>Cross-Posting</h3>
                    <p>Ein Post, alle Plattformen. Automatische Anpassung an jedes Format.</p>
                </div>
                <div class="feature">
                    <p class="feature-number">02</p>
                    <h3>Smart Scheduling</h3>
                    <p>Plane deine Woche in 15 Minuten. Wir zeigen dir wann deine Audience online ist.</p>
                </div>
                <div class="feature">
                    <p class="feature-number">03</p>
                    <h3>Content-Werkzeuge</h3>
                    <p>Hook-Vorschlaege und Hashtag-Recherche. Du bleibst authentisch.</p>
                </div>
                <div class="feature">
                    <p class="feature-number">04</p>
                    <h3>Analytics</h3>
                    <p>Was hat funktioniert und warum. Keine 47 Dashboards.</p>
                </div>
            </div>
        </section>

        <section class="bordered">
            <p class="section-label">So funktioniert's</p>
            <h2>In 4 Schritten zum Post</h2>

            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Verbinden</h3>
                    <p>Social Accounts verknuepfen</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Erstellen</h3>
                    <p>Post schreiben & Medien hochladen</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Planen</h3>
                    <p>Zeit waehlen oder sofort posten</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Fertig</h3>
                    <p>Postamt uebernimmt den Rest</p>
                </div>
            </div>
        </section>

        <section class="bordered" id="pricing">
            <p class="section-label">Preise</p>
            <h2 style="text-align: center;">Einfach. Fair.</h2>

            <div class="pricing-card">
                <span class="pricing-badge">Early Access</span>

                <h3>Creator Plan</h3>

                <div class="price">
                    <span class="original">19 EUR</span>
                    <span class="amount">9 EUR</span>
                    <span class="period">/Monat</span>
                </div>

                <ul class="pricing-features">
                    <li>5 Social Media Accounts</li>
                    <li>Unbegrenzte Posts</li>
                    <li>Alle Plattformen</li>
                    <li>Smart Scheduling</li>
                    <li>Analytics Dashboard</li>
                    <li>Deutscher Support</li>
                </ul>

                <button class="btn-primary" onclick="scrollToWaitlist()">Jetzt starten</button>

                <p style="margin-top: 12px; font-size: 12px; color: #999;">
                    Erste 100 User: Dieser Preis bleibt fuer immer.
                </p>
            </div>
        </section>
    </div>

    <section style="padding: 80px 0;">
        <div class="final-cta">
            <h2>Bereit?</h2>
            <p>Starte jetzt mit Postamt.</p>
            <div class="cta-box">
                <input type="email" class="email-input" placeholder="deine@email.de" id="waitlist-email-2">
                <button class="btn-primary" onclick="joinWaitlist(true)">Warteliste beitreten</button>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>Postamt – Gebaut fuer Creator</p>
        </div>
    </footer>

    <div class="toast" id="toast"></div>

    <script>
        function showToast(message, type) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast ' + type + ' show';
            setTimeout(() => {
                toast.className = 'toast ' + type;
            }, 3000);
        }

        async function joinWaitlist(useSecondInput) {
            const inputId = useSecondInput ? 'waitlist-email-2' : 'waitlist-email';
            const email = document.getElementById(inputId).value.trim();

            if (!email || !email.includes('@')) {
                showToast('Bitte gib eine gueltige Email-Adresse ein.', 'error');
                return;
            }

            const btn = document.getElementById('waitlist-btn');
            btn.disabled = true;
            btn.textContent = 'Wird gesendet...';

            try {
                const response = await fetch('/api/waitlist', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email })
                });

                const data = await response.json();

                if (response.ok) {
                    showToast('Du bist auf der Warteliste!', 'success');
                    document.getElementById('waitlist-email').value = '';
                    document.getElementById('waitlist-email-2').value = '';
                    loadWaitlistCount();
                } else {
                    showToast(data.error || 'Etwas ist schiefgelaufen.', 'error');
                }
            } catch (err) {
                showToast('Verbindungsfehler. Bitte versuche es spaeter.', 'error');
            }

            btn.disabled = false;
            btn.textContent = 'Warteliste beitreten';
        }

        async function loadWaitlistCount() {
            try {
                const response = await fetch('/api/waitlist/count');
                const data = await response.json();
                document.getElementById('waitlist-count').textContent =
                    data.count + ' Creator auf der Warteliste';
            } catch (err) {
                document.getElementById('waitlist-count').textContent = '';
            }
        }

        function scrollToWaitlist() {
            document.getElementById('waitlist-email').focus();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        document.getElementById('waitlist-email').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') joinWaitlist();
        });
        document.getElementById('waitlist-email-2').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') joinWaitlist(true);
        });

        loadWaitlistCount();

        // Butterfly fly-in animation for social icons
        function animatePlatforms() {
            const platforms = document.querySelectorAll('.platform');
            const platformsContainer = document.querySelector('.platforms');

            if (!platformsContainer) return;

            // Store original positions
            const originalPositions = [];
            platforms.forEach((platform, index) => {
                const rect = platform.getBoundingClientRect();
                originalPositions.push({
                    left: rect.left + window.scrollX,
                    top: rect.top + window.scrollY,
                    width: rect.width,
                    height: rect.height
                });
            });

            // Generate random starting positions from edges of screen
            platforms.forEach((platform, index) => {
                const edge = Math.floor(Math.random() * 4); // 0: top, 1: right, 2: bottom, 3: left
                let startX, startY;

                switch(edge) {
                    case 0: // top
                        startX = Math.random() * window.innerWidth;
                        startY = -100;
                        break;
                    case 1: // right
                        startX = window.innerWidth + 100;
                        startY = Math.random() * window.innerHeight;
                        break;
                    case 2: // bottom
                        startX = Math.random() * window.innerWidth;
                        startY = window.innerHeight + 100;
                        break;
                    case 3: // left
                        startX = -100;
                        startY = Math.random() * window.innerHeight;
                        break;
                }

                // Set initial flying position
                platform.classList.add('flying');
                platform.style.left = startX + 'px';
                platform.style.top = startY + 'px';

                // Animate to destination with bezier curve path
                const delay = index * 150 + Math.random() * 200;
                const duration = 1500 + Math.random() * 500;

                setTimeout(() => {
                    animateToDestination(platform, startX, startY, originalPositions[index], duration, index);
                }, delay);
            });
        }

        function animateToDestination(platform, startX, startY, dest, duration, index) {
            const startTime = performance.now();

            // Create waypoints for curved butterfly-like path
            const midX1 = startX + (dest.left - startX) * 0.3 + (Math.random() - 0.5) * 200;
            const midY1 = startY + (dest.top - startY) * 0.3 + (Math.random() - 0.5) * 150;
            const midX2 = startX + (dest.left - startX) * 0.7 + (Math.random() - 0.5) * 150;
            const midY2 = startY + (dest.top - startY) * 0.7 + (Math.random() - 0.5) * 100;

            function bezier(t, p0, p1, p2, p3) {
                const u = 1 - t;
                return u*u*u*p0 + 3*u*u*t*p1 + 3*u*t*t*p2 + t*t*t*p3;
            }

            function animate(currentTime) {
                const elapsed = currentTime - startTime;
                let progress = Math.min(elapsed / duration, 1);

                // Ease out cubic for smooth landing
                const eased = 1 - Math.pow(1 - progress, 3);

                const x = bezier(eased, startX, midX1, midX2, dest.left);
                const y = bezier(eased, startY, midY1, midY2, dest.top);

                platform.style.left = x + 'px';
                platform.style.top = y + 'px';

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    // Landing complete
                    platform.classList.remove('flying');
                    platform.classList.add('landed');
                    platform.style.left = '';
                    platform.style.top = '';
                    platform.style.position = '';
                }
            }

            requestAnimationFrame(animate);
        }

        // Start animation when page loads and platforms section is visible
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', animatePlatforms);
        } else {
            animatePlatforms();
        }
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="Analytics" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
