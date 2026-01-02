<?php
/**
 * Application Bootstrap
 * Loads configuration and classes without routing
 */

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Load configuration
require_once __DIR__ . '/config.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/Lib/' . $class . '.php',
        __DIR__ . '/Controllers/' . $class . '.php',
        __DIR__ . '/Models/' . $class . '.php',
        __DIR__ . '/Services/' . $class . '.php',
        __DIR__ . '/Publishers/' . $class . '.php',
        __DIR__ . '/Validators/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
