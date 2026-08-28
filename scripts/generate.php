#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use WarpPanel\Images\Generator;

$rootDir = dirname(__DIR__);
$catalogOnly = in_array('--catalog-only', $argv, true);

try {
    $generator = new Generator($rootDir);
    $generator->run($catalogOnly);
    echo "✓ [Composer/PHP] Generation completed successfully! All Dockerfiles, docker-bake.hcl and catalog files updated.\n";
} catch (\Throwable $e) {
    fwrite(STDERR, "[!] Error during generation: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n");
    exit(1);
}
