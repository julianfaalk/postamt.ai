<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postamt.ai – Social Media Management. Einfach.</title>
    <meta name="description" content="Poste auf Instagram, TikTok, YouTube, LinkedIn, X, Facebook, Threads, Pinterest, Bluesky, Snapchat, WhatsApp und Telegram gleichzeitig. Plane deine Social Media Woche in 15 Minuten. Fuer Creator die es ernst meinen.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://postamt.ai/">

    <!-- Open Graph -->
    <meta property="og:title" content="Postamt.ai – Social Media Management. Einfach.">
    <meta property="og:description" content="Poste auf Instagram, TikTok, YouTube, LinkedIn, X, Facebook, Threads, Pinterest und mehr gleichzeitig. Plane deine Social Media Woche in 15 Minuten.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://postamt.ai/">
    <meta property="og:site_name" content="Postamt">
    <meta property="og:locale" content="de_DE">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Postamt.ai  – Social Media Management. Einfach.">
    <meta name="twitter:description" content="Poste auf Instagram, TikTok, YouTube, LinkedIn, X, Facebook, Threads, Pinterest und mehr gleichzeitig. Plane deine Social Media Woche in 15 Minuten.">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏤</text></svg>">

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

        html, body {
            overflow-x: hidden;
            max-width: 100vw;
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
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .logo-emoji {
            font-size: 24px;
        }

        .hero-emoji-container {
            margin-top: 48px;
            text-align: center;
        }

        .hero-emoji {
            font-size: 64px;
            display: inline-block;
            animation: gentleBounce 3s ease-in-out infinite;
        }

        @keyframes gentleBounce {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
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

        /* Platforms - positioned row above headline */
        .platforms-row {
            position: relative;
            height: 50px;
            margin-bottom: 32px;
            width: 100%;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .platform-icon {
            position: absolute;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
        }

        .platform-icon:hover {
            transform: scale(1.15);
            transition: transform 0.2s ease;
        }

        .platform-icon svg {
            width: 32px;
            height: 32px;
        }

        /* Platform colors - just the SVG fill */
        .platform-icon.instagram svg { fill: url(#instagram-gradient); }
        .platform-icon.tiktok svg { fill: #000; }
        .platform-icon.youtube svg { fill: #FF0000; }
        .platform-icon.linkedin svg { fill: #0A66C2; }
        .platform-icon.x svg { fill: #000; }
        .platform-icon.bluesky svg { fill: #0085ff; }
        .platform-icon.snapchat svg { fill: #FFFC00; }
        .platform-icon.whatsapp svg { fill: #25D366; }
        .platform-icon.telegram svg { fill: #0088cc; }
        .platform-icon.facebook svg { fill: #1877F2; }
        .platform-icon.threads svg { fill: #000; }
        .platform-icon.pinterest svg { fill: #E60023; }

        /* Tooltip for platform icons */
        .platform-icon::after {
            content: attr(data-tooltip);
            position: absolute;
            top: -32px;
            left: 50%;
            transform: translateX(-50%);
            background: #111;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }
        .platform-icon.landed:hover::after {
            opacity: 1;
        }

        /* Smooth butterfly animation */
        .platform-icon.flying {
            z-index: 1000;
            pointer-events: none;
            opacity: 1;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));
        }

        .platform-icon.flying svg {
            animation: gentleFloat 1s ease-in-out infinite;
        }

        @keyframes gentleFloat {
            0%, 100% { transform: rotate(-5deg) scale(1.05); }
            50% { transform: rotate(5deg) scale(1.1); }
        }

        .platform-icon.landed {
            opacity: 1;
        }

        .platform-icon.landed svg {
            animation: softLand 0.3s ease-out forwards;
        }

        @keyframes softLand {
            0% { transform: scale(1.15); }
            60% { transform: scale(0.95); }
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

            .hero {
                padding: 60px 0 40px;
            }

            .hero p {
                font-size: 16px;
                padding: 0 10px;
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

            .platforms-row {
                height: 40px;
                max-width: 340px;
            }

            .platform-icon {
                width: 26px;
                height: 26px;
            }

            .platform-icon svg {
                width: 22px;
                height: 22px;
            }

            .platform-icon::after {
                display: none;
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
                padding: 40px 20px;
            }

            .final-cta h2 {
                font-size: 24px;
            }

            .pricing-card {
                margin: 0 -8px;
                padding: 24px 20px;
            }

            .price .amount {
                font-size: 36px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 16px;
            }

            .hero {
                padding: 40px 0 30px;
            }

            h1 {
                font-size: 26px;
            }

            section h2 {
                font-size: 22px;
            }

            .steps {
                grid-template-columns: 1fr;
            }

            .platforms-row {
                height: 35px;
                max-width: 280px;
            }

            .platform-icon {
                width: 22px;
                height: 22px;
            }

            .platform-icon svg {
                width: 18px;
                height: 18px;
            }

            .hero-emoji {
                font-size: 48px;
            }

            .badge {
                font-size: 12px;
                padding: 5px 12px;
            }

            .feature {
                padding: 18px;
            }

            .feature h3 {
                font-size: 16px;
            }

            .feature p {
                font-size: 13px;
            }

            .toast {
                left: 16px;
                right: 16px;
                bottom: 16px;
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
            <a href="/" class="logo"><span class="logo-emoji">🏤</span> Postamt.ai</a>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#pricing">Preise</a>
                <a href="/login">Login</a>
            </div>
        </div>
    </nav>

    <!-- SVG Gradient definitions -->
    <svg width="0" height="0" style="position:absolute">
        <defs>
            <linearGradient id="instagram-gradient" x1="0%" y1="100%" x2="100%" y2="0%">
                <stop offset="0%" style="stop-color:#FFDC80"/>
                <stop offset="25%" style="stop-color:#F77737"/>
                <stop offset="50%" style="stop-color:#F56040"/>
                <stop offset="75%" style="stop-color:#C13584"/>
                <stop offset="100%" style="stop-color:#833AB4"/>
            </linearGradient>
        </defs>
    </svg>

    <div class="container">
        <section class="hero">
            <!-- Platform icons row - above headline -->
            <div class="platforms-row">
                <div class="platform-icon x" data-tooltip="X / Twitter">
                    <svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </div>
                <div class="platform-icon instagram" data-tooltip="Instagram">
                    <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </div>
                <div class="platform-icon linkedin" data-tooltip="LinkedIn">
                    <svg viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </div>
                <div class="platform-icon tiktok" data-tooltip="TikTok">
                    <svg viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                </div>
                <div class="platform-icon youtube" data-tooltip="YouTube">
                    <svg viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </div>
                <div class="platform-icon facebook" data-tooltip="Facebook">
                    <svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </div>
                <div class="platform-icon threads" data-tooltip="Threads">
                    <svg viewBox="0 0 192 192"><path d="M141.537 88.988a66.667 66.667 0 0 0-2.518-1.143c-1.482-27.307-16.403-42.94-41.457-43.1h-.34c-14.986 0-27.449 6.396-35.12 18.036l13.779 9.452c5.73-8.695 14.724-10.548 21.348-10.548h.229c8.249.053 14.474 2.452 18.503 7.129 2.932 3.405 4.893 8.111 5.864 14.05-7.314-1.243-15.224-1.626-23.68-1.14-23.82 1.371-39.134 15.264-38.105 34.568.522 9.792 5.4 18.216 13.735 23.719 7.047 4.652 16.124 6.927 25.557 6.412 12.458-.683 22.231-5.436 29.049-14.127 5.178-6.6 8.453-15.153 9.899-25.93 5.937 3.583 10.337 8.298 12.767 13.966 4.132 9.635 4.373 25.468-8.546 38.376-11.319 11.308-24.925 16.2-45.488 16.351-22.809-.169-40.06-7.484-51.275-21.742C35.236 139.966 29.808 120.682 29.6 96c.208-24.682 5.636-43.966 16.128-57.317 11.215-14.258 28.466-21.573 51.275-21.742 22.996.173 40.526 7.557 52.128 21.958 5.673 7.034 9.908 15.793 12.632 26.007l15.623-4.186c-3.2-12.011-8.39-22.503-15.496-31.308C146.882 10.858 125.314 1.61 97.042 1.4h-.075C68.819 1.612 47.603 10.932 33.006 29.63 16.948 49.94 10.465 76.905 10.2 95.964v.075c.265 19.059 6.748 46.024 22.806 66.334 14.597 18.698 35.813 28.018 64.061 28.23h.075c24.578-.177 41.605-6.555 56.124-21.025 19.128-19.082 18.376-42.8 12.007-57.598-4.593-10.663-12.928-19.238-24.106-24.868-.079-.042-.158-.083-.238-.124l.6.001Zm-41.14 44.905c-10.444.572-21.294-4.098-21.86-14.711-.396-7.442 5.296-15.746 22.461-16.735 1.966-.114 3.895-.169 5.79-.169 6.235 0 12.068.606 17.371 1.765-1.978 24.702-13.58 29.274-23.762 29.85Z"/></svg>
                </div>
                <div class="platform-icon pinterest" data-tooltip="Pinterest">
                    <svg viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/></svg>
                </div>
                <div class="platform-icon bluesky" data-tooltip="Bluesky">
                    <svg viewBox="0 0 24 24"><path d="M12 10.8c-1.087-2.114-4.046-6.053-6.798-7.995C2.566.944 1.561 1.266.902 1.565.139 1.908 0 3.08 0 3.768c0 .69.378 5.65.624 6.479.815 2.736 3.713 3.66 6.383 3.364.136-.02.275-.039.415-.056-.138.022-.276.04-.415.056-3.912.58-7.387 2.005-2.83 7.078 5.013 5.19 6.87-1.113 7.823-4.308.953 3.195 2.05 9.271 7.733 4.308 4.267-4.308 1.172-6.498-2.74-7.078a8.741 8.741 0 0 1-.415-.056c.14.017.279.036.415.056 2.67.297 5.568-.628 6.383-3.364.246-.828.624-5.79.624-6.478 0-.69-.139-1.861-.902-2.206-.659-.298-1.664-.62-4.3 1.24C16.046 4.748 13.087 8.687 12 10.8z"/></svg>
                </div>
                <div class="platform-icon snapchat" data-tooltip="Snapchat">
                    <svg viewBox="0 0 512 512"><path d="M496.926,366.6c-3.373-9.176-9.8-14.086-17.112-18.153-1.376-.806-2.641-1.451-3.72-1.947-2.182-1.128-4.414-2.22-6.634-3.373-22.8-12.09-40.609-27.341-52.959-45.42a102.889,102.889,0,0,1-9.089-16.12c-1.054-3.013-1-4.724-.248-6.287a10.221,10.221,0,0,1,2.914-3.038c3.918-2.591,7.96-5.22,10.7-6.993,4.885-3.162,8.754-5.667,11.246-7.44,9.362-6.547,15.909-13.5,20-21.278a42.371,42.371,0,0,0,2.1-35.191c-6.2-16.318-21.613-26.449-40.287-26.449a55.543,55.543,0,0,0-11.718,1.24c-1.029.224-2.059.459-3.063.72.174-11.16-.074-22.94-1.066-34.534-3.522-40.758-17.794-62.123-32.674-79.16A159.718,159.718,0,0,0,313.522,20.9C291.5,7.2,266.865,0,241.191,0h-5.767c-25.674,0-50.46,7.2-72.477,20.905A159.63,159.63,0,0,0,110.8,63.481c-14.88,17.038-29.152,38.4-32.674,79.161-.992,11.594-1.24,23.374-1.066,34.534-1-.261-2.034-.5-3.063-.72a55.719,55.719,0,0,0-11.717-1.24c-18.675,0-34.086,10.131-40.3,26.449a42.423,42.423,0,0,0,2.046,35.228c4.105,7.774,10.652,14.731,20.014,21.278,2.48,1.736,6.361,4.24,11.246,7.44,2.641,1.711,6.5,4.216,10.28,6.72a11.053,11.053,0,0,1,3.3,3.311c.794,1.563.831,3.288-.261,6.357a102.392,102.392,0,0,1-8.9,15.881c-12.316,18.079-30.166,33.33-53,45.42-2.108,1.116-4.328,2.245-6.6,3.373-1.203.56-2.714,1.29-4.4,2.157-6.697,3.843-12.91,8.542-16.372,17.569a35.821,35.821,0,0,0,.122,27.066c6.036,14.247,21.818,24.267,47.839,30.375,2.145.5,4.4.968,6.473,1.389,1.711.348,3.311.672,4.548.968,1.513.362,2.9,2.468,3.6,7.030.794,5.468,1.873,11.131,3.982,16.665,2.2,5.7,6.1,8.678,10.893,10.5a63.142,63.142,0,0,0,16.769,3.61,128.267,128.267,0,0,0,16.654-.062,191.477,191.477,0,0,1,20.09-.968c6.472,0,13.655.968,21.439,2.852,16.851,4.079,33.591,17.93,52.959,34.534,19.517,16.727,45.606,26.412,71.56,26.549H258.7c25.965-.137,52.054-9.822,71.56-26.549,19.368-16.6,36.108-30.455,52.959-34.534,7.784-1.884,14.967-2.852,21.438-2.852a191.54,191.54,0,0,1,20.091.968,140.309,140.309,0,0,0,16.654.062,63.036,63.036,0,0,0,16.768-3.61c4.787-1.809,8.693-4.787,10.906-10.5,2.109-5.534,3.187-11.2,3.982-16.665.077-.512.186-1.029.31-1.563,1.7-7.377,5.092-8.79,8.281-9.376,2.033-.4,3.97-.794,5.791-1.24,6.156-1.451,41.634-10.442,47.888-30.375A35.758,35.758,0,0,0,496.926,366.6Z"/></svg>
                </div>
                <div class="platform-icon whatsapp" data-tooltip="WhatsApp">
                    <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <div class="platform-icon telegram" data-tooltip="Telegram">
                    <svg viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                </div>
            </div>

            <h1>Social Media Management.<br>Einfach.</h1>

            <p>Poste auf allen Plattformen gleichzeitig. Plane deine Woche in 15 Minuten. Fuer Creator die es ernst meinen.</p>

            <div class="cta-box">
                <input type="email" class="email-input" placeholder="deine@email.de" id="waitlist-email">
                <button class="btn-primary" id="waitlist-btn" onclick="joinWaitlist()">Warteliste beitreten</button>
            </div>

            <p class="social-proof" id="waitlist-count">Lade...</p>

            <div class="hero-emoji-container">
                <span class="hero-emoji">🏤</span>
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
            <span style="font-size: 48px; display: block; margin-bottom: 16px;">🏤</span>
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
            <p>🏤 Postamt – Gebaut fuer Creator</p>
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

        // Smooth fly-in animation for social icons
        function animatePlatforms() {
            const icons = document.querySelectorAll('.platform-icon');
            const container = document.querySelector('.platforms-row');

            if (!container || !icons.length) return;

            const containerRect = container.getBoundingClientRect();
            const containerWidth = containerRect.width;
            const iconCount = icons.length;
            const iconSize = 36;
            const totalWidth = iconCount * iconSize + (iconCount - 1) * 16; // icons + gaps
            const startOffset = (containerWidth - totalWidth) / 2;

            // Fixed final positions (evenly spaced in the container)
            const finalPositions = [];
            icons.forEach((icon, index) => {
                const finalX = startOffset + index * (iconSize + 16);
                const finalY = 7; // vertically centered in 50px container
                finalPositions.push({ x: finalX, y: finalY });
            });

            // Animate each icon from random start to fixed end
            icons.forEach((icon, index) => {
                const final = finalPositions[index];

                // Random start position (scattered around viewport)
                const angle = Math.random() * Math.PI * 2;
                const distance = 250 + Math.random() * 200;

                // Start positions relative to container center
                const startX = containerWidth / 2 + Math.cos(angle) * distance;
                const startY = Math.sin(angle) * distance;

                // Position icon at start
                icon.style.left = startX + 'px';
                icon.style.top = startY + 'px';
                icon.classList.add('flying');

                // Staggered animation start
                const delay = index * 80;
                const duration = 1400 + Math.random() * 300;

                setTimeout(() => {
                    flyToPosition(icon, startX, startY, final.x, final.y, duration);
                }, delay);
            });
        }

        function flyToPosition(icon, startX, startY, endX, endY, duration) {
            const startTime = performance.now();

            // Control point for smooth curve (arc upward)
            const midX = (startX + endX) / 2 + (Math.random() - 0.5) * 80;
            const midY = Math.min(startY, endY) - 60 - Math.random() * 80;

            function animate(currentTime) {
                const elapsed = currentTime - startTime;
                const t = Math.min(elapsed / duration, 1);

                // Smooth ease-out cubic
                const eased = 1 - Math.pow(1 - t, 3);

                // Quadratic bezier curve
                const u = 1 - eased;
                const x = u * u * startX + 2 * u * eased * midX + eased * eased * endX;
                const y = u * u * startY + 2 * u * eased * midY + eased * eased * endY;

                icon.style.left = x + 'px';
                icon.style.top = y + 'px';

                if (t < 1) {
                    requestAnimationFrame(animate);
                } else {
                    // Set exact final position
                    icon.style.left = endX + 'px';
                    icon.style.top = endY + 'px';
                    icon.style.filter = '';
                    icon.classList.remove('flying');
                    icon.classList.add('landed');
                }
            }

            requestAnimationFrame(animate);
        }

        // Start animation when page is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(animatePlatforms, 300);
            });
        } else {
            setTimeout(animatePlatforms, 300);
        }
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="Analytics" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
