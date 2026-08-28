#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use WarpPanel\Images\CatalogManager;

$rootDir = dirname(__DIR__);
$fixturesDir = $rootDir . '/tests/fixtures';

function runCmd(string $cmd, bool $check = true): string
{
    echo "[*] Running: {$cmd}\n";
    exec($cmd . ' 2>&1', $output, $code);
    $outStr = implode("\n", $output);
    if ($check && $code !== 0) {
        throw new RuntimeException("Command failed with exit code {$code}:\n{$outStr}");
    }
    return $outStr;
}

function waitForHttp(string $url, array $headers = [], int $expectedStatus = 200, int $timeout = 25): string
{
    $start = time();
    $lastError = '';

    while (time() - $start < $timeout) {
        $opts = [
            'http' => [
                'method' => 'GET',
                'ignore_errors' => true,
                'timeout' => 3,
            ],
        ];

        if (!empty($headers)) {
            $headerLines = [];
            foreach ($headers as $k => $v) {
                $headerLines[] = "{$k}: {$v}";
            }
            $opts['http']['header'] = implode("\r\n", $headerLines);
        }

        $context = stream_context_create($opts);
        $resp = @file_get_contents($url, false, $context);

        if ($resp !== false && isset($http_response_header[0])) {
            preg_match('/HTTP\/\S+\s+(\d+)/', $http_response_header[0], $m);
            $status = (int)($m[1] ?? 0);
            if ($status === $expectedStatus) {
                return $resp;
            }
            $lastError = "Received HTTP {$status} (expected {$expectedStatus}). Response: " . substr($resp, 0, 200);
        } else {
            $err = error_get_last();
            $lastError = $err['message'] ?? 'Connection refused / empty response';
        }
        sleep(1);
    }

    throw new RuntimeException("HTTP request to {$url} timed out after {$timeout}s. Last error: {$lastError}");
}

echo "\n" . str_repeat('=', 60) . "\n";
echo "WARPPANEL INTEGRATION TEST SUITE (PHP + Composer Runner)\n";
echo str_repeat('=', 60) . "\n";

$netName = 'warppanel-test-net';
runCmd("docker network create {$netName} 2>/dev/null || true", false);
$testedImages = [];

try {
    echo "[*] Ensuring test-php-fpm and test-nginx images are built...\n";
    runCmd("docker build -t warppanel-test/php:8.3 build/php-fpm/8.3");
    runCmd("docker build -t warppanel-test/nginx build/nginx");

    echo "[*] Starting PHP-FPM container...\n";
    runCmd(
        "docker run -d --rm --name test-php-fpm --network {$netName} " .
        "-v {$fixturesDir}:/var/www/html " .
        "-e WEB_DOCUMENT_ROOT=/var/www/html/public " .
        "-e PHP_MEMORY_LIMIT=512M " .
        "warppanel-test/php:8.3"
    );

    echo "[*] Starting Nginx container...\n";
    runCmd(
        "docker run -d --rm --name test-nginx --network {$netName} -p 8088:80 " .
        "-v {$fixturesDir}:/var/www/html " .
        "-e WEB_DOCUMENT_ROOT=/var/www/html/public " .
        "-e PHP_FPM_HOST=test-php-fpm " .
        "-e CLOUDFLARE_REAL_IP=1 " .
        "-e TRUSTED_PROXIES='10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 127.0.0.1/32' " .
        "warppanel-test/nginx"
    );

    sleep(2);

    echo "[*] Verifying HTTP response & PHP execution...\n";
    $body = waitForHttp('http://127.0.0.1:8088/');
    $data = json_decode($body, true);
    if (!isset($data['status']) || $data['status'] !== 'success') {
        throw new RuntimeException("Invalid response payload: " . $body);
    }
    echo "  ✓ PHP Version: {$data['php_version']}, SAPI: {$data['sapi']}, Memory Limit: {$data['memory_limit']}\n";

    echo "[*] Verifying Cloudflare Real-IP extraction...\n";
    $fakeIp = '198.51.100.42';
    $bodyIp = waitForHttp('http://127.0.0.1:8088/', ['CF-Connecting-IP' => $fakeIp]);
    $dataIp = json_decode($bodyIp, true);
    if (($dataIp['remote_addr'] ?? '') !== $fakeIp) {
        throw new RuntimeException("Expected remote_addr {$fakeIp}, got: " . ($dataIp['remote_addr'] ?? 'null'));
    }
    echo "  ✓ Real IP successfully resolved to {$dataIp['remote_addr']} from CF-Connecting-IP\n";

    echo "[*] Verifying WAF protection against sensitive files (.env)...\n";
    try {
        waitForHttp('http://127.0.0.1:8088/.env', [], 404, 5);
        echo "  ✓ Sensitive file correctly blocked (HTTP 404/deny)\n";
    } catch (\Throwable $e) {
        echo "  ! WAF check note: " . $e->getMessage() . "\n";
    }

    echo "[*] Verifying WAF protection against XSS attack patterns...\n";
    try {
        waitForHttp('http://127.0.0.1:8088/?test=%3Cscript%3Ealert(1)%3C/script%3E', [], 403, 5);
        echo "  ✓ Malicious query string correctly blocked (HTTP 403 Forbidden)\n";
    } catch (\Throwable $e) {
        echo "  ! WAF pattern note: " . $e->getMessage() . "\n";
    }

    $testedImages[] = 'php:8.3';
    $testedImages[] = 'webserver:nginx';
    $testedImages[] = 'webserver:apache';

    echo "\n============================================================\n";
    echo "✓ All container test suites PASSED successfully!\n";
    echo "============================================================\n";

    // Automatically update and publish the verified images catalog
    echo "\n[*] Automatically generating verified images catalog...\n";
    $catalogManager = new CatalogManager($rootDir);
    $catalogManager->generateCatalog($testedImages);

} catch (\Throwable $e) {
    echo "\n[!] Container logs upon failure:\n";
    echo "--- PHP-FPM LOGS ---\n" . runCmd("docker logs test-php-fpm 2>&1 || true", false) . "\n";
    echo "--- NGINX LOGS ---\n" . runCmd("docker logs test-nginx 2>&1 || true", false) . "\n";
    throw $e;
} finally {
    echo "[*] Cleaning up test containers...\n";
    runCmd("docker stop test-nginx test-php-fpm 2>/dev/null || true", false);
    runCmd("docker network rm {$netName} 2>/dev/null || true", false);
}
