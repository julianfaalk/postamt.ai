<?php
/**
 * Database Migration Script
 * Run: php src/migrate-db.php
 */

require_once __DIR__ . '/config.php';

$db = new PDO('sqlite:' . DB_PATH);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Running migrations...\n";

$migrations = [
    // Migration 1: Add OAuth columns to users table
    'add_oauth_columns' => function($db) {
        // Check if columns already exist
        $columns = $db->query("PRAGMA table_info(users)")->fetchAll(PDO::FETCH_ASSOC);
        $columnNames = array_column($columns, 'name');

        if (!in_array('google_id', $columnNames)) {
            $db->exec("ALTER TABLE users ADD COLUMN google_id TEXT UNIQUE");
            echo "  - Added google_id column\n";
        }

        if (!in_array('avatar_url', $columnNames)) {
            $db->exec("ALTER TABLE users ADD COLUMN avatar_url TEXT");
            echo "  - Added avatar_url column\n";
        }

        if (!in_array('timezone', $columnNames)) {
            $db->exec("ALTER TABLE users ADD COLUMN timezone TEXT DEFAULT 'Europe/Berlin'");
            echo "  - Added timezone column\n";
        }

        // Create index for google_id if not exists
        try {
            $db->exec("CREATE INDEX IF NOT EXISTS idx_users_google_id ON users(google_id)");
            echo "  - Created index on google_id\n";
        } catch (Exception $e) {
            // Index might already exist
        }
    },

    // Migration 2: Add retry_count to post_platforms
    'add_retry_count' => function($db) {
        $columns = $db->query("PRAGMA table_info(post_platforms)")->fetchAll(PDO::FETCH_ASSOC);
        $columnNames = array_column($columns, 'name');

        if (!in_array('retry_count', $columnNames)) {
            $db->exec("ALTER TABLE post_platforms ADD COLUMN retry_count INTEGER DEFAULT 0");
            echo "  - Added retry_count column to post_platforms\n";
        }

        if (!in_array('platform_post_url', $columnNames)) {
            $db->exec("ALTER TABLE post_platforms ADD COLUMN platform_post_url TEXT");
            echo "  - Added platform_post_url column to post_platforms\n";
        }
    },

    // Migration 3: Add more analytics columns
    'extend_analytics' => function($db) {
        $columns = $db->query("PRAGMA table_info(analytics)")->fetchAll(PDO::FETCH_ASSOC);
        $columnNames = array_column($columns, 'name');

        if (!in_array('impressions', $columnNames)) {
            $db->exec("ALTER TABLE analytics ADD COLUMN impressions INTEGER DEFAULT 0");
            echo "  - Added impressions column to analytics\n";
        }

        if (!in_array('reach', $columnNames)) {
            $db->exec("ALTER TABLE analytics ADD COLUMN reach INTEGER DEFAULT 0");
            echo "  - Added reach column to analytics\n";
        }

        if (!in_array('engagement_rate', $columnNames)) {
            $db->exec("ALTER TABLE analytics ADD COLUMN engagement_rate REAL");
            echo "  - Added engagement_rate column to analytics\n";
        }
    },

    // Migration 4: Add media table
    'create_media_table' => function($db) {
        $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='media'")->fetchColumn();

        if (!$tables) {
            $db->exec("
                CREATE TABLE media (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    post_id INTEGER,
                    original_filename TEXT,
                    stored_filename TEXT NOT NULL,
                    mime_type TEXT NOT NULL,
                    file_size INTEGER NOT NULL,
                    width INTEGER,
                    height INTEGER,
                    duration INTEGER,
                    storage_path TEXT NOT NULL,
                    thumbnail_path TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE SET NULL
                )
            ");
            $db->exec("CREATE INDEX IF NOT EXISTS idx_media_user_id ON media(user_id)");
            $db->exec("CREATE INDEX IF NOT EXISTS idx_media_post_id ON media(post_id)");
            echo "  - Created media table\n";
        }
    },
];

foreach ($migrations as $name => $migration) {
    echo "\nMigration: {$name}\n";
    try {
        $migration($db);
        echo "  Done.\n";
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
}

echo "\nMigrations completed!\n";
