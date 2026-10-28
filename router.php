<?php
// Built-in PHP development server router for Kampung Cameron

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
$file = __DIR__ . '/public' . $uri;

if ($uri !== '/' && file_exists($file) && is_file($file)) {
    // Serve static files with proper Content-Type
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $mimes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'woff2'=> 'font/woff2',
        'json' => 'application/json',
    ];
    $contentType = $mimes[$ext] ?? mime_content_type($file) ?? 'application/octet-stream';
    header("Content-Type: $contentType");
    readfile($file);
    exit;
}

// Pass all dynamic routes to public/index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/public/index.php';
