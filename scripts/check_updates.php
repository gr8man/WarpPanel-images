#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use WarpPanel\Images\VersionChecker;
use WarpPanel\Images\Generator;

$rootDir = dirname(__DIR__);

try {
    $checker = new VersionChecker($rootDir);
    $result = $checker->checkAndApplyUpdates();

    if ($result['updated']) {
        echo "[*] Re-generating templates and manifests...\n";
        $generator = new Generator($rootDir);
        $generator->run();

        // Write summary file for GitHub Actions
        $summaryFile = $rootDir . '/updates-summary.md';
        $summary = "### 📦 Upstream Container Updates Detected\n\n";
        foreach ($result['log'] as $line) {
            $summary .= "- " . $line . "\n";
        }
        file_put_contents($summaryFile, $summary);
        echo "✓ Updates summary saved to updates-summary.md\n";
        exit(0);
    }

    echo "✓ No updates needed.\n";
    exit(0);
} catch (\Throwable $e) {
    fwrite(STDERR, "[!] Error during version check: " . $e->getMessage() . "\n");
    exit(1);
}
