<?php
// Kampung Cameron System Configuration

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Credentials
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'kampung_cameron');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL Auto-detection (clean project root URL, stripping /public if present)
$base = '';
if (!empty($_SERVER['DOCUMENT_ROOT'])) {
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    $projectRoot = str_replace('\\', '/', realpath(dirname(__DIR__)));
    if ($docRoot && $projectRoot && str_starts_with($projectRoot, $docRoot)) {
        $base = substr($projectRoot, strlen($docRoot));
    }
}
if ($base === '' && !empty($_SERVER['SCRIPT_NAME'])) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $base = preg_replace('#/public$#', '', $scriptDir);
}
$base = ($base === '/' || $base === '\\') ? '' : rtrim(str_replace('\\', '/', $base), '/');
define('BASE_URL', $base);
define('CURRENCY_SYMBOL', 'RM');

// App Paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/views');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
