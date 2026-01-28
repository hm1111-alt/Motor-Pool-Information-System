<?php
/**
 * Router for PHP built-in server
 */

// Define the base path
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Remove the base path
$uri = '/' . ltrim($uri, '/');

// Don't execute for assets that exist as real files
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Otherwise, send everything to Laravel's front controller
require_once __DIR__ . '/index.php';