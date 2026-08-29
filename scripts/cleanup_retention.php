#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use WarpPanel\Images\CatalogManager;

$rootDir = dirname(__DIR__);
$catalogManager = new CatalogManager($rootDir);
$retentionDays = 30;

echo "[*] Running 30-day retention cleanup for CURRENT channel manifests...\n";

$deletedFiles = $catalogManager->cleanExpiredCurrentBuilds($retentionDays);

if (!empty($deletedFiles)) {
    echo "✓ Removed " . count($deletedFiles) . " expired manifest files older than {$retentionDays} days:\n";
    foreach ($deletedFiles as $df) {
        echo "  - {$df}\n";
    }
} else {
    echo "✓ No expired manifests found (all current manifests are within {$retentionDays} days retention).\n";
}

// 2. GHCR Container Image Tag Cleanup (Optional via GitHub REST API in CI)
$token = getenv('GITHUB_TOKEN');
$repoOwner = getenv('GITHUB_REPOSITORY_OWNER') ?: 'gr8man';

if ($token && function_exists('curl_init')) {
    echo "[*] Checking GHCR for current-channel container tags older than {$retentionDays} days...\n";
    $cutoffDate = new DateTime("-{$retentionDays} days");

    // List of container packages managed by WarpPanel
    $packages = ['php', 'frankenphp', 'nginx', 'apache', 'openlitespeed', 'mysql', 'mariadb', 'postgres', 'redis', 'mongodb', 'sqlite'];

    foreach ($packages as $pkg) {
        $url = "https://api.github.com/users/{$repoOwner}/packages/container/{$pkg}/versions?per_page=100";
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$token}",
                "Accept: application/vnd.github+json",
                "User-Agent: WarpPanel-Retention-Cleanup",
            ],
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $versions = json_decode($response, true);
            if (is_array($versions)) {
                foreach ($versions as $ver) {
                    $tags = $ver['metadata']['container']['tags'] ?? [];
                    $createdAt = new DateTime($ver['created_at']);

                    // Only prune if older than 30 days and NOT tagged with stable
                    $hasStableTag = false;
                    $hasCurrentDatedTag = false;
                    foreach ($tags as $t) {
                        if (str_contains($t, 'stable')) {
                            $hasStableTag = true;
                            break;
                        }
                        if (preg_match('/-\d{8}$/', $t) || str_contains($t, 'current')) {
                            $hasCurrentDatedTag = true;
                        }
                    }

                    if (!$hasStableTag && $hasCurrentDatedTag && $createdAt < $cutoffDate) {
                        $verId = $ver['id'];
                        echo "  [PRUNE GHCR] Deleting expired current image version: {$pkg} (ID: {$verId}, Tags: " . implode(', ', $tags) . ", Date: {$ver['created_at']})\n";

                        $delUrl = "https://api.github.com/users/{$repoOwner}/packages/container/{$pkg}/versions/{$verId}";
                        $delCh = curl_init($delUrl);
                        curl_setopt_array($delCh, [
                            CURLOPT_CUSTOMREQUEST => 'DELETE',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_HTTPHEADER => [
                                "Authorization: Bearer {$token}",
                                "Accept: application/vnd.github+json",
                                "User-Agent: WarpPanel-Retention-Cleanup",
                            ],
                        ]);
                        curl_exec($delCh);
                        curl_close($delCh);
                    }
                }
            }
        }
    }
}

echo "✓ Retention cleanup completed successfully.\n";
