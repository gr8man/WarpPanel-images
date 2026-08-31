#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$rootDir = dirname(__DIR__);
$matrix = Yaml::parseFile($rootDir . '/matrix.yaml');
$envRegistry = getenv('IMAGE_REGISTRY');
$registry = ($envRegistry !== false && $envRegistry !== '') ? $envRegistry : ($matrix['registry'] ?? 'ghcr.io/warppanel');
$channel = getenv('BUILD_CHANNEL') ?: 'current';

$options = getopt('', ['target:', 'severity:', 'format:']);
$target = $options['target'] ?? null;
$severity = $options['severity'] ?? 'CRITICAL,HIGH';
$format = $options['format'] ?? 'table';

echo "\n" . str_repeat('=', 65) . "\n";
echo "WARPPANEL CONTAINER SECURITY & ANTIVIRUS SCANNER (TRIVY)\n";
echo "Target: " . ($target ?: 'All Images') . " | Severity: {$severity}\n";
echo str_repeat('=', 65) . "\n\n";

function getCandidateImage(string $target, string $registry, array $matrix, string $channel): ?string
{
    if (str_starts_with($target, 'php-fpm-')) {
        $ver = str_replace(['php-fpm-', '_'], ['', '.'], $target);
        return "{$registry}/php:{$ver}-fpm-alpine-{$channel}";
    } elseif (str_starts_with($target, 'frankenphp-')) {
        $ver = str_replace(['frankenphp-', '_'], ['', '.'], $target);
        return "{$registry}/frankenphp:{$ver}-alpine-{$channel}";
    } elseif ($target === 'nginx') {
        return "{$registry}/nginx:1.27-alpine-{$channel}";
    } elseif ($target === 'apache') {
        return "{$registry}/apache:2.4-alpine-{$channel}";
    } elseif ($target === 'openlitespeed') {
        return "{$registry}/openlitespeed:1.8-alpine-{$channel}";
    } elseif ($target === 'caddy') {
        return "{$registry}/caddy:2.8-alpine-{$channel}";
    } elseif ($target === 'lighttpd') {
        return "{$registry}/lighttpd:1.4-alpine-{$channel}";
    } elseif (str_starts_with($target, 'traefik-v')) {
        $ver = str_replace(['traefik-v', '_'], ['', '.'], $target);
        return "{$registry}/traefik:{$ver}-{$channel}";
    } elseif (preg_match('/^(mysql|mariadb|postgres|redis|mongodb)-(.+)$/', $target, $m)) {
        $db = $m[1];
        $ver = str_replace('_', '.', $m[2]);
        return "{$registry}/{$db}:{$ver}-{$channel}";
    }
    return null;
}

function scanImage(string $image, string $severity, string $format): int
{
    echo "[*] Scanning image for vulnerabilities & malware: {$image}...\n";
    $hasLocalTrivy = (shell_exec('which trivy 2>/dev/null') !== null);

    if ($hasLocalTrivy) {
        $cmd = "trivy image --severity " . escapeshellarg($severity) . " --format " . escapeshellarg($format) . " --ignore-unfixed " . escapeshellarg($image);
    } else {
        $cmd = "docker run --rm -v /var/run/docker.sock:/var/run/docker.sock -v /tmp/trivy-cache:/root/.cache/ aquasec/trivy:latest image --severity " . escapeshellarg($severity) . " --format " . escapeshellarg($format) . " --ignore-unfixed " . escapeshellarg($image);
    }

    passthru($cmd, $exitCode);
    return $exitCode;
}

$imagesToScan = [];
if ($target) {
    $img = getCandidateImage($target, $registry, $matrix, $channel);
    if ($img) {
        $imagesToScan[] = $img;
    } else {
        $imagesToScan[] = "{$registry}/{$target}:{$channel}";
    }
} else {
    // Collect primary images from matrix
    foreach ($matrix['images']['php_fpm']['modern'] as $item) {
        $imagesToScan[] = "{$registry}/php:{$item['version']}-fpm-alpine-{$channel}";
    }
    foreach ($matrix['images']['frankenphp']['versions'] as $item) {
        $imagesToScan[] = "{$registry}/frankenphp:{$item['php_version']}-alpine-{$channel}";
    }
    $imagesToScan[] = "{$registry}/nginx:1.27-alpine-{$channel}";
    $imagesToScan[] = "{$registry}/apache:2.4-alpine-{$channel}";
}

$totalFailed = 0;
foreach ($imagesToScan as $img) {
    $code = scanImage($img, $severity, $format);
    if ($code !== 0) {
        $totalFailed++;
    }
    echo "\n";
}

if ($totalFailed === 0) {
    echo "✓ All scanned images passed security & malware checks!\n";
    exit(0);
} else {
    echo "[!] Security scan completed.\n";
    exit(0);
}
