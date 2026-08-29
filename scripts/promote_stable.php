#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use WarpPanel\Images\CatalogManager;

$rootDir = dirname(__DIR__);
$catalogManager = new CatalogManager($rootDir);

echo "[*] Checking for software/package/runtime changes to promote into STABLE channel...\n";

$changed = $catalogManager->promoteStableIfChanged();

if (empty($changed)) {
    echo "✓ No changes detected in software versions or packages. Stable channel remains unchanged.\n";
    exit(0);
}

echo "⚡ [STABLE PROMOTION] The following " . count($changed) . " image targets have updated software/packages:\n";
foreach ($changed as $img) {
    echo "  - [{$img['category']}/{$img['item']}]\n";
    foreach ($img['reasons'] as $r) {
        echo "      * {$r}\n";
    }
    echo "      * Source Image: {$img['source_image']}\n";
    echo "      * Stable Tags:  " . implode(', ', $img['stable_tags']) . "\n";
}

// Write GitHub Actions step output if running in CI
$githubOutput = getenv('GITHUB_OUTPUT');
if ($githubOutput && file_exists($githubOutput)) {
    file_put_contents($githubOutput, "has_stable_promotions=true\n", FILE_APPEND);
}

echo "✓ Stable manifests updated in catalog/stable/.\n";
