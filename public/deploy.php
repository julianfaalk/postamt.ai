<?php
// Deploy webhook - GitHub calls this, it pulls and rebuilds Docker
chdir(dirname(__DIR__));

// Pull latest code
echo "=== Git Pull ===\n";
echo shell_exec('git pull 2>&1');

// Force rebuild without cache (needed because COPY uses layer cache)
echo "\n=== Docker Build ===\n";
echo shell_exec('docker compose build --no-cache 2>&1');

echo "\n=== Docker Up ===\n";
echo shell_exec('docker compose up -d 2>&1');
