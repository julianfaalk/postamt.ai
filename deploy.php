<?php
// Deploy webhook - GitHub calls this, it pulls and rebuilds Docker
chdir(__DIR__);
echo shell_exec('git pull 2>&1');
echo shell_exec('docker compose up -d --build 2>&1');
