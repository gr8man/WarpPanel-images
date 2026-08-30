<?php
header('Content-Type: application/json');

$exts = get_loaded_extensions();
$extensionsWithVersion = [];
foreach ($exts as $ext) {
    $ver = phpversion($ext);
    $extensionsWithVersion[$ext] = ($ver !== false && $ver !== '') ? $ver : PHP_VERSION;
}
ksort($extensionsWithVersion);

$systemPackages = [];
if (file_exists('/lib/apk/db/installed') && is_readable('/lib/apk/db/installed')) {
    $content = file_get_contents('/lib/apk/db/installed');
    $lines = explode("\n", $content);
    $currentPkg = null;
    foreach ($lines as $line) {
        if (strncmp($line, 'P:', 2) === 0) {
            $currentPkg = trim(substr($line, 2));
        } elseif (strncmp($line, 'V:', 2) === 0 && $currentPkg !== null) {
            $systemPackages[$currentPkg] = trim(substr($line, 2));
            $currentPkg = null;
        }
    }
    ksort($systemPackages);
}

$response = [
    'status' => 'success',
    'php_version' => PHP_VERSION,
    'sapi' => PHP_SAPI,
    'remote_addr' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
    'http_cf_connecting_ip' => isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : null,
    'http_x_forwarded_for' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null,
    'http_host' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null,
    'server_software' => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : null,
    'document_root' => isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : null,
    'memory_limit' => ini_get('memory_limit'),
    'opcache_enabled' => function_exists('opcache_get_status') ? (bool)opcache_get_status(false) : false,
    'loaded_extensions' => $exts,
    'php_extensions' => $extensionsWithVersion,
    'system_packages' => $systemPackages,
    'runtime_defaults' => [
        'user' => get_current_user(),
        'uid' => function_exists('posix_getuid') ? posix_getuid() : getmyuid(),
        'gid' => function_exists('posix_getgid') ? posix_getgid() : getmygid(),
        'document_root' => isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : null,
    ],
    'php_ini' => [
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'max_input_vars' => ini_get('max_input_vars'),
        'date.timezone' => ini_get('date.timezone'),
        'display_errors' => ini_get('display_errors'),
        'opcache.enable' => ini_get('opcache.enable'),
        'xdebug.mode' => ini_get('xdebug.mode'),
    ],
];

echo json_encode($response, JSON_PRETTY_PRINT);



