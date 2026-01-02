<?php
/**
 * Application Configuration
 */

define('APP_NAME', 'Postamt');
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('APP_DEBUG', getenv('APP_DEBUG') === 'true' || APP_ENV === 'development');

// Database
define('DB_PATH', __DIR__ . '/../data/database.sqlite');

// Session
define('SESSION_LIFETIME', 86400 * 7); // 7 days

// Upload limits
define('MAX_UPLOAD_SIZE', 50 * 1024 * 1024); // 50MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/quicktime', 'video/webm']);

// Platform API credentials (to be configured)
define('TWITTER_CLIENT_ID', getenv('TWITTER_CLIENT_ID') ?: '');
define('TWITTER_CLIENT_SECRET', getenv('TWITTER_CLIENT_SECRET') ?: '');

define('INSTAGRAM_CLIENT_ID', getenv('INSTAGRAM_CLIENT_ID') ?: '');
define('INSTAGRAM_CLIENT_SECRET', getenv('INSTAGRAM_CLIENT_SECRET') ?: '');

define('TIKTOK_CLIENT_KEY', getenv('TIKTOK_CLIENT_KEY') ?: '');
define('TIKTOK_CLIENT_SECRET', getenv('TIKTOK_CLIENT_SECRET') ?: '');

define('LINKEDIN_CLIENT_ID', getenv('LINKEDIN_CLIENT_ID') ?: '');
define('LINKEDIN_CLIENT_SECRET', getenv('LINKEDIN_CLIENT_SECRET') ?: '');

define('YOUTUBE_CLIENT_ID', getenv('YOUTUBE_CLIENT_ID') ?: '');
define('YOUTUBE_CLIENT_SECRET', getenv('YOUTUBE_CLIENT_SECRET') ?: '');

// Base URL
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost:8080');
