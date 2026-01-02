<?php
/**
 * Sitemap Generator
 * Generates sitemap.xml from SEO pages and static pages
 * Run: php /var/www/html/src/generate-sitemap.php
 */

require_once __DIR__ . '/config.php';

$db = new PDO('sqlite:' . DB_PATH);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$baseUrl = 'https://postamt.ai';

echo "Generating sitemap...\n";

// Start XML
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Static pages
$staticPages = [
    ['url' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['url' => '/login', 'priority' => '0.5', 'changefreq' => 'monthly'],
    ['url' => '/register', 'priority' => '0.6', 'changefreq' => 'monthly'],
];

foreach ($staticPages as $page) {
    $xml .= generateUrlEntry($baseUrl . $page['url'], date('Y-m-d'), $page['changefreq'], $page['priority']);
}

// SEO Pages from database
$stmt = $db->query('SELECT slug, page_type, updated_at FROM seo_pages ORDER BY page_type, slug');
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$priorityMap = [
    'platform' => '0.9',
    'combination' => '0.8',
    'comparison' => '0.8',
    'audience' => '0.7',
    'guide' => '0.7',
];

foreach ($pages as $page) {
    $priority = $priorityMap[$page['page_type']] ?? '0.6';
    $lastmod = date('Y-m-d', strtotime($page['updated_at']));
    $xml .= generateUrlEntry($baseUrl . $page['slug'], $lastmod, 'weekly', $priority);
}

$xml .= '</urlset>';

// Write sitemap
$sitemapPath = __DIR__ . '/../public/sitemap.xml';
file_put_contents($sitemapPath, $xml);

echo "Sitemap generated at: {$sitemapPath}\n";
echo "Total URLs: " . (count($staticPages) + count($pages)) . "\n";

// Generate robots.txt if it doesn't exist
$robotsPath = __DIR__ . '/../public/robots.txt';
if (!file_exists($robotsPath)) {
    $robots = <<<TXT
User-agent: *
Allow: /

Sitemap: {$baseUrl}/sitemap.xml
TXT;
    file_put_contents($robotsPath, $robots);
    echo "robots.txt generated at: {$robotsPath}\n";
}

function generateUrlEntry($loc, $lastmod, $changefreq, $priority) {
    return <<<XML
  <url>
    <loc>{$loc}</loc>
    <lastmod>{$lastmod}</lastmod>
    <changefreq>{$changefreq}</changefreq>
    <priority>{$priority}</priority>
  </url>

XML;
}
