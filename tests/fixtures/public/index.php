<?php
header('Content-Type: application/json');

$response = [
    'status' => 'success',
    'php_version' => PHP_VERSION,
    'sapi' => PHP_SAPI,
    'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? null,
    'http_cf_connecting_ip' => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null,
    'http_x_forwarded_for' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
    'http_host' => $_SERVER['HTTP_HOST'] ?? null,
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? null,
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? null,
    'memory_limit' => ini_get('memory_limit'),
    'opcache_enabled' => function_exists('opcache_get_status') ? (bool)opcache_get_status(false) : false,
    'loaded_extensions' => get_loaded_extensions(),
];

echo json_encode($response, JSON_PRETTY_PRINT);
