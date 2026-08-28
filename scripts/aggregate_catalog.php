#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use WarpPanel\Images\CatalogManager;

$rootDir = dirname(__DIR__);
$artifactsDir = $argv[1] ?? ($rootDir . '/verifications');

echo "[*] Aggregating verification results from: {$artifactsDir}\n";

$catalogManager = new CatalogManager($rootDir);
$catalog = $catalogManager->aggregateFromDirectory($artifactsDir);

echo "✓ Aggregation complete. Total verified images: " . ($catalog['summary']['verified_count'] ?? 0) . "\n";
