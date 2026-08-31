<?php

declare(strict_types=1);

namespace WarpPanel\Images;

use Symfony\Component\Yaml\Yaml;

class CatalogManager
{
    private string $rootDir;
    private string $matrixFile;
    private string $catalogJsonFile;
    private string $catalogMdFile;
    private string $availableImagesJsonFile;
    private string $catalogDetailsDir;

    public function __construct(string $rootDir)
    {
        $this->rootDir = rtrim($rootDir, '/');
        $this->matrixFile = $this->rootDir . '/matrix.yaml';
        $this->catalogJsonFile = $this->rootDir . '/catalog.json';
        $this->catalogMdFile = $this->rootDir . '/CATALOG.md';
        $this->availableImagesJsonFile = $this->rootDir . '/available-images.json';
        $this->catalogDetailsDir = $this->rootDir . '/catalog';
    }

    public function recordVerification(string $target, string $status = 'VERIFIED_PASS', array $meta = []): string
    {
        $data = [
            'target' => $target,
            'status' => $status,
            'timestamp' => date('c'),
            'metadata' => $meta,
        ];

        $outputDir = $this->rootDir . '/verifications';
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $filename = "{$outputDir}/verification-{$target}.json";
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo "✓ Verification record saved: {$filename}\n";

        return $filename;
    }

    public function aggregateFromDirectory(string $dir): array
    {
        $verifiedTargets = [];
        $verifiedMetadata = [];
        if (is_dir($dir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && str_starts_with($file->getFilename(), 'verification-') && $file->getExtension() === 'json') {
                    $json = json_decode(file_get_contents($file->getRealPath()), true);
                    if (!empty($json['target']) && ($json['status'] ?? '') === 'VERIFIED_PASS') {
                        $target = $json['target'];
                        $verifiedTargets[] = $target;
                        if (!empty($json['metadata'])) {
                            $verifiedMetadata[$target] = $json['metadata'];
                        }
                        echo "  [+] Found verified target: {$target} (from {$file->getFilename()})\n";
                    }
                }
            }
        }

        $verifiedTargets = array_unique($verifiedTargets);
        return $this->generateCatalog($verifiedTargets, $verifiedMetadata);
    }

    public function generateCatalog(array $verifiedTargets = [], array $verifiedMetadata = []): array
    {
        $matrix = Yaml::parseFile($this->matrixFile);
        $envRegistry = getenv('IMAGE_REGISTRY');
        $registry = ($envRegistry !== false && $envRegistry !== '') ? $envRegistry : ($matrix['registry'] ?? 'ghcr.io/warppanel');
        $buildDate = getenv('BUILD_DATE') ?: date('Ymd');
        $channel = getenv('BUILD_CHANNEL') ?: 'current';
        $now = date('c');

        $channelDir = $this->catalogDetailsDir . "/{$channel}";
        $this->ensureDir($this->catalogDetailsDir);
        $this->ensureDir($channelDir);
        $this->ensureDir($channelDir . '/php-fpm');
        $this->ensureDir($channelDir . '/frankenphp');
        $this->ensureDir($channelDir . '/webservers');
        $this->ensureDir($channelDir . '/databases');

        // Load existing catalog if available to preserve history of builds
        $existingCatalog = [];
        if (file_exists($this->catalogJsonFile)) {
            $existingCatalog = json_decode(file_get_contents($this->catalogJsonFile), true) ?: [];
        }
        $availableBuilds = $existingCatalog['available_builds'] ?? [];
        if (!in_array($buildDate, $availableBuilds, true)) {
            $availableBuilds[] = $buildDate;
            sort($availableBuilds);
        }

        $totalDatabases = 0;
        if (!empty($matrix['images']['databases'])) {
            foreach ($matrix['images']['databases'] as $dbItems) {
                $totalDatabases += count($dbItems);
            }
        }

        $totalTraefik = !empty($matrix['images']['traefik']['versions']) ? count($matrix['images']['traefik']['versions']) : 0;

        $catalog = [
            'version' => '1.0',
            'channel' => $channel,
            'updated_at' => $now,
            'current_build_id' => $buildDate,
            'available_builds' => $availableBuilds,
            'registry' => $registry,
            'summary' => [
                'total_php_versions' => count($matrix['images']['php_fpm']['legacy']) + count($matrix['images']['php_fpm']['modern']),
                'total_frankenphp_versions' => count($matrix['images']['frankenphp']['versions']),
                'total_webservers' => count($matrix['images']['webservers']),
                'total_traefik_versions' => $totalTraefik,
                'total_databases' => $totalDatabases,
                'verified_count' => count($verifiedTargets),
            ],
            'images' => [
                'php_fpm' => [],
                'frankenphp' => [],
                'webservers' => [],
                'traefik' => [],
                'databases' => [],
            ],
        ];

        $simpleList = [
            'registry' => $registry,
            'channel' => $channel,
            'updated_at' => $now,
            'build_date' => $buildDate,
            'php' => [],
            'frankenphp' => [],
            'webservers' => [],
            'traefik' => [],
            'databases' => [],
        ];

        // 1. PHP-FPM Modern
        foreach ($matrix['images']['php_fpm']['modern'] as $img) {
            $ver = $img['version'];
            $targetKey = 'php-fpm-' . str_replace('.', '_', $ver);
            $fullTags = $this->expandTags($img['tags'], $registry, 'php', $buildDate, $channel);
            $rawTags = $this->expandRawTags($img['tags'], $buildDate, $channel);
            $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("php:{$ver}", $verifiedTargets, true);
            $meta = $verifiedMetadata[$targetKey] ?? [];
            $detectedPhpVer = $meta['php_version'] ?? $ver;
            $runtimeExts = $meta['php_extensions'] ?? [];
            $runtimePkgs = $meta['system_packages'] ?? [];
            $configuredExts = $matrix['defaults']['php_extensions'] ?? [];
            $extensionsWithVersions = $this->buildExtensionsMap($configuredExts, $detectedPhpVer, $runtimeExts);
            $packagesWithVersions = $this->buildSystemPackagesMap(['bash', 'curl', 'ca-certificates', 'git', 'unzip', 'zip', 'msmtp', 'shadow', 'tzdata', 'sqlite'], $runtimePkgs);

            $versionDir = $channelDir . "/php-fpm/{$ver}";
            $this->ensureDir($versionDir);
            $detailRelPath = "catalog/{$channel}/php-fpm/{$ver}/{$buildDate}.json";
            $latestRelPath = "catalog/{$channel}/php-fpm/{$ver}/latest.json";

            $phpIniSettings = $matrix['defaults']['php_settings'] ?? [];
            if (!empty($meta['php_ini'])) {
                $phpIniSettings = array_merge($phpIniSettings, array_filter($meta['php_ini']));
            }

            $detailData = [
                'name' => "WarpPanel PHP-FPM {$ver} Modern ({$channel})",
                'category' => 'php_fpm',
                'channel' => $channel,
                'version' => $ver,
                'build_id' => $buildDate,
                'build_date' => $buildDate,
                'build_timestamp' => $now,
                'exact_php_version' => $detectedPhpVer,
                'target' => $targetKey,
                'type' => 'modern',
                'base_image' => $img['base_image'],
                'registry' => $registry,
                'primary_tag' => $fullTags[1] ?? $fullTags[0],
                'tags' => $rawTags,
                'full_image_tags' => $fullTags,
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
                'software_stack' => [
                    'php' => [
                        'version' => $detectedPhpVer,
                        'sapi' => 'fpm-fcgi (PHP-FPM)',
                        'opcache_jit' => true,
                        'process_manager' => 'ondemand / dynamic',
                    ],
                    'composer' => [
                        'version' => '2.x (Latest)',
                        'binary' => '/usr/local/bin/composer',
                    ],
                    'xdebug' => [
                        'version' => '3.x',
                        'default_state' => 'disabled',
                        'toggle_variable' => 'PHP_XDEBUG_ENABLED=1',
                    ],
                    'sqlite' => [
                        'support' => 'built-in',
                        'pdo_sqlite' => 'enabled',
                        'sqlite3' => 'enabled',
                    ],
                    'database_drivers' => ['pdo_mysql', 'mysqli', 'pdo_sqlite', 'sqlite3', 'pdo_pgsql', 'pgsql', 'redis (phpredis)'],
                    'os' => [
                        'distribution' => 'Alpine Linux',
                        'base' => 'Official Docker PHP Alpine',
                    ],
                    'system_packages' => $packagesWithVersions,
                ],
                'php_extensions' => $extensionsWithVersions,
                'runtime_defaults' => [
                    'user' => $meta['runtime_defaults']['user'] ?? 'www-data',
                    'puid' => (int)($meta['runtime_defaults']['uid'] ?? 1000),
                    'pgid' => (int)($meta['runtime_defaults']['gid'] ?? 1000),
                    'app_dir' => '/var/www/html',
                    'document_root' => $meta['runtime_defaults']['document_root'] ?? '/var/www/html/public',
                    'port' => 9000,
                    'php_ini' => $phpIniSettings,
                ],
            ];

            file_put_contents(
                $this->rootDir . '/' . $detailRelPath,
                json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
            file_put_contents(
                $this->rootDir . '/' . $latestRelPath,
                json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

            $summaryItem = [
                'version' => $ver,
                'channel' => $channel,
                'build_id' => $buildDate,
                'target' => $targetKey,
                'type' => 'PHP-FPM Modern',
                'base_image' => $img['base_image'],
                'primary_tag' => $fullTags[1] ?? $fullTags[0],
                'floating_tag' => $fullTags[0],
                'tags' => $rawTags,
                'full_image_tags' => $fullTags,
                'details_file' => $detailRelPath,
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
            ];
            $catalog['images']['php_fpm'][] = $summaryItem;
            if ($isVerified) {
                $simpleList['php'][$ver] = $fullTags;
            }
        }

        // 2. PHP-FPM Legacy
        foreach ($matrix['images']['php_fpm']['legacy'] as $img) {
            $ver = $img['version'];
            $targetKey = 'php-fpm-' . str_replace('.', '_', $ver);
            $fullTags = $this->expandTags($img['tags'], $registry, 'php', $buildDate, $channel);
            $rawTags = $this->expandRawTags($img['tags'], $buildDate, $channel);
            $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("php:{$ver}", $verifiedTargets, true);
            $meta = $verifiedMetadata[$targetKey] ?? [];
            $detectedPhpVer = $meta['php_version'] ?? $ver;
            $runtimeExts = $meta['php_extensions'] ?? [];
            $runtimePkgs = $meta['system_packages'] ?? [];
            $composerVer = in_array($ver, ['5.6', '7.0', '7.1'], true) ? '2.2 LTS (PHP <= 7.1 compatible)' : '2.x (Latest)';
            $xdebugVer = in_array($ver, ['5.6', '7.0'], true) ? '2.5.x' : (in_array($ver, ['7.1'], true) ? '2.9.x' : '3.x');
            $legacyConfiguredExts = ['pdo_mysql', 'pdo_sqlite', 'sqlite3', 'mysqli', 'gd', 'opcache', 'curl', 'zip', 'intl', 'mbstring', 'bcmath', 'pdo'];
            $extensionsWithVersions = $this->buildExtensionsMap($legacyConfiguredExts, $detectedPhpVer, $runtimeExts);
            $packagesWithVersions = $this->buildSystemPackagesMap(['bash', 'curl', 'ca-certificates', 'git', 'unzip', 'zip', 'msmtp', 'shadow', 'tzdata', 'sqlite'], $runtimePkgs);

            $versionDir = $channelDir . "/php-fpm/{$ver}";
            $this->ensureDir($versionDir);
            $detailRelPath = "catalog/{$channel}/php-fpm/{$ver}/{$buildDate}.json";
            $latestRelPath = "catalog/{$channel}/php-fpm/{$ver}/latest.json";

            $phpIniSettings = $matrix['defaults']['php_settings'] ?? [];
            if (!empty($meta['php_ini'])) {
                $phpIniSettings = array_merge($phpIniSettings, array_filter($meta['php_ini']));
            }

            $detailData = [
                'name' => "WarpPanel PHP-FPM {$ver} Legacy ({$channel})",
                'category' => 'php_fpm',
                'channel' => $channel,
                'version' => $ver,
                'build_id' => $buildDate,
                'build_date' => $buildDate,
                'build_timestamp' => $now,
                'exact_php_version' => $detectedPhpVer,
                'target' => $targetKey,
                'type' => 'legacy',
                'base_image' => $img['base_image'],
                'registry' => $registry,
                'primary_tag' => $fullTags[1] ?? $fullTags[0],
                'tags' => $rawTags,
                'full_image_tags' => $fullTags,
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
                'software_stack' => [
                    'php' => [
                        'version' => $detectedPhpVer,
                        'sapi' => 'fpm-fcgi (PHP-FPM)',
                    ],
                    'composer' => [
                        'version' => $composerVer,
                        'binary' => '/usr/local/bin/composer',
                    ],
                    'xdebug' => [
                        'version' => $xdebugVer,
                        'default_state' => 'disabled',
                        'toggle_variable' => 'PHP_XDEBUG_ENABLED=1',
                    ],
                    'sqlite' => [
                        'support' => 'built-in',
                        'pdo_sqlite' => 'enabled',
                        'sqlite3' => 'enabled',
                    ],
                    'database_drivers' => ['pdo_mysql', 'mysqli', 'pdo_sqlite', 'sqlite3', 'redis'],
                    'os' => [
                        'distribution' => 'Alpine Linux',
                        'base' => 'Official Docker PHP Alpine',
                    ],
                    'system_packages' => $packagesWithVersions,
                ],
                'php_extensions' => $extensionsWithVersions,
                'runtime_defaults' => [
                    'user' => $meta['runtime_defaults']['user'] ?? 'www-data',
                    'puid' => (int)($meta['runtime_defaults']['uid'] ?? 1000),
                    'pgid' => (int)($meta['runtime_defaults']['gid'] ?? 1000),
                    'app_dir' => '/var/www/html',
                    'document_root' => $meta['runtime_defaults']['document_root'] ?? '/var/www/html/public',
                    'port' => 9000,
                    'php_ini' => $phpIniSettings,
                ],
            ];

            file_put_contents(
                $this->rootDir . '/' . $detailRelPath,
                json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
            file_put_contents(
                $this->rootDir . '/' . $latestRelPath,
                json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

            $summaryItem = [
                'version' => $ver,
                'channel' => $channel,
                'build_id' => $buildDate,
                'target' => $targetKey,
                'type' => 'PHP-FPM Legacy',
                'base_image' => $img['base_image'],
                'primary_tag' => $fullTags[1] ?? $fullTags[0],
                'floating_tag' => $fullTags[0],
                'tags' => $rawTags,
                'full_image_tags' => $fullTags,
                'details_file' => $detailRelPath,
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
            ];
            $catalog['images']['php_fpm'][] = $summaryItem;
            if ($isVerified) {
                $simpleList['php'][$ver] = $fullTags;
            }
        }

        // 3. FrankenPHP
        foreach ($matrix['images']['frankenphp']['versions'] as $img) {
            $ver = $img['php_version'];
            $targetKey = 'frankenphp-' . str_replace('.', '_', $ver);
            $fullTags = $this->expandTags($img['tags'], $registry, 'frankenphp', $buildDate, $channel);
            $rawTags = $this->expandRawTags($img['tags'], $buildDate, $channel);
            $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("frankenphp:{$ver}", $verifiedTargets, true);
            $meta = $verifiedMetadata[$targetKey] ?? [];
            $detectedPhpVer = $meta['php_version'] ?? $ver;
            $runtimeExts = $meta['php_extensions'] ?? [];
            $runtimePkgs = $meta['system_packages'] ?? [];
            $configuredExts = $matrix['defaults']['php_extensions'] ?? [];
            $extensionsWithVersions = $this->buildExtensionsMap($configuredExts, $detectedPhpVer, $runtimeExts);
            $packagesWithVersions = $this->buildSystemPackagesMap(['bash', 'curl', 'ca-certificates', 'git', 'unzip', 'zip', 'msmtp', 'shadow', 'tzdata'], $runtimePkgs);

            $versionDir = $channelDir . "/frankenphp/{$ver}";
            $this->ensureDir($versionDir);
            $detailRelPath = "catalog/{$channel}/frankenphp/{$ver}/{$buildDate}.json";
            $latestRelPath = "catalog/{$channel}/frankenphp/{$ver}/latest.json";

            $detailData = [
                'name' => "WarpPanel FrankenPHP (PHP {$ver}) All-in-One Runtime ({$channel})",
                'category' => 'frankenphp',
                'channel' => $channel,
                'php_version' => $ver,
                'build_id' => $buildDate,
                'build_date' => $buildDate,
                'build_timestamp' => $now,
                'exact_php_version' => $detectedPhpVer,
                'target' => $targetKey,
                'base_image' => $img['base_image'],
                'registry' => $registry,
                'primary_tag' => $fullTags[1] ?? $fullTags[0],
                'tags' => $rawTags,
                'full_image_tags' => $fullTags,
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
                'software_stack' => [
                    'engine' => 'FrankenPHP (Go / Caddy Module)',
                    'webserver' => 'Caddy v2 with Automatic HTTPS',
                    'php' => [
                        'version' => $detectedPhpVer,
                        'worker_mode' => 'supported (Octane, Laravel, Symfony, Custom)',
                    ],
                    'protocols' => ['HTTP/1.1', 'HTTP/2', 'HTTP/3 (QUIC)'],
                    'composer' => [
                        'version' => '2.x (Latest)',
                        'binary' => '/usr/local/bin/composer',
                    ],
                    'xdebug' => [
                        'version' => '3.x',
                        'default_state' => 'disabled',
                        'toggle_variable' => 'PHP_XDEBUG_ENABLED=1',
                    ],
                    'sqlite' => [
                        'support' => 'built-in',
                        'pdo_sqlite' => 'enabled',
                        'sqlite3' => 'enabled',
                    ],
                    'security_layer' => 'Cloudflare Real-IP Restoration + WAF Rules',
                    'system_packages' => $packagesWithVersions,
                ],
                'php_extensions' => $extensionsWithVersions,
                'runtime_defaults' => [
                    'user' => $meta['runtime_defaults']['user'] ?? 'www-data',
                    'puid' => (int)($meta['runtime_defaults']['uid'] ?? 1000),
                    'pgid' => (int)($meta['runtime_defaults']['gid'] ?? 1000),
                    'app_dir' => '/var/www/html',
                    'document_root' => $meta['runtime_defaults']['document_root'] ?? '/var/www/html/public',
                    'ports' => [80, 443, '443/udp'],
                ],
            ];

            file_put_contents(
                $this->rootDir . '/' . $detailRelPath,
                json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
            file_put_contents(
                $this->rootDir . '/' . $latestRelPath,
                json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

            $summaryItem = [
                'php_version' => $ver,
                'channel' => $channel,
                'build_id' => $buildDate,
                'target' => $targetKey,
                'type' => 'FrankenPHP Runtime',
                'base_image' => $img['base_image'],
                'primary_tag' => $fullTags[1] ?? $fullTags[0],
                'floating_tag' => $fullTags[0],
                'tags' => $rawTags,
                'full_image_tags' => $fullTags,
                'details_file' => $detailRelPath,
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
            ];
            $catalog['images']['frankenphp'][] = $summaryItem;
            if ($isVerified) {
                $simpleList['frankenphp'][$ver] = $fullTags;
            }
        }

        // 4. Webservers
        foreach ($matrix['images']['webservers'] as $srvName => $srvCfg) {
            $targetKey = $srvName;
            $fullTags = $this->expandTags($srvCfg['tags'], $registry, $srvName, $buildDate, $channel);
            $rawTags = $this->expandRawTags($srvCfg['tags'], $buildDate, $channel);
            $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("webserver:{$srvName}", $verifiedTargets, true);

            $srvDir = $channelDir . "/webservers/{$srvName}";
            $this->ensureDir($srvDir);
            $detailRelPath = "catalog/{$channel}/webservers/{$srvName}/{$buildDate}.json";
            $latestRelPath = "catalog/{$channel}/webservers/{$srvName}/latest.json";

            $stackInfo = [];
            if ($srvName === 'nginx') {
                $stackInfo = [
                    'software' => 'Nginx Mainline (Alpine Linux)',
                    'protocols' => ['HTTP/1.1', 'HTTP/2', 'HTTP/3 (QUIC)'],
                    'compression' => ['Gzip', 'Brotli'],
                    'upstream' => 'FastCGI Support (PHP-FPM :9000)',
                    'security' => ['Cloudflare Real-IP (CF-Connecting-IP)', 'WAF Rules (.env, .git, .sql blocking)', 'Security Headers'],
                    'ports' => [80, 443, '443/udp'],
                ];
            } elseif ($srvName === 'apache') {
                $stackInfo = [
                    'software' => 'Apache HTTP Server (httpd 2.4 Alpine)',
                    'mpm' => 'mpm_event (High Concurrency Multi-Processing)',
                    'modules' => ['mod_proxy_fcgi', 'mod_rewrite', 'mod_remoteip (Cloudflare)', 'mod_headers', 'mod_deflate', 'mod_ssl', 'mod_http2'],
                    'security' => ['WAF Rules (.env, .git, .sql blocking)'],
                    'ports' => [80, 443],
                ];
            } elseif ($srvName === 'openlitespeed') {
                $stackInfo = [
                    'software' => 'OpenLiteSpeed High-Performance Server',
                    'cache_engine' => 'LSCache (LiteSpeed Page Cache Engine)',
                    'protocols' => ['HTTP/2', 'HTTP/3 (QUIC)'],
                    'security' => ['Built-in WAF rules', 'Cloudflare Real-IP'],
                    'ports' => [80, 443, 7080],
                ];
            } elseif ($srvName === 'caddy') {
                $stackInfo = [
                    'software' => 'Caddy v2 Standalone Web Server',
                    'engine' => 'Caddy v2 (Go)',
                    'protocols' => ['HTTP/1.1', 'HTTP/2', 'HTTP/3 (QUIC)'],
                    'compression' => ['Zstd', 'Gzip'],
                    'upstream' => 'FastCGI Support (PHP-FPM :9000)',
                    'security' => ['Auto-HTTPS ACME', 'Cloudflare Real-IP', 'WAF Rules'],
                    'ports' => [80, 443, '443/udp'],
                ];
            } elseif ($srvName === 'lighttpd') {
                $stackInfo = [
                    'software' => 'Lighttpd Ultra-Light Web Server (Alpine Linux)',
                    'protocols' => ['HTTP/1.1'],
                    'compression' => ['Gzip (mod_deflate)'],
                    'upstream' => 'FastCGI Support (PHP-FPM :9000)',
                    'security' => ['Cloudflare Real-IP (mod_extforward)', 'WAF Rules'],
                    'ports' => [80],
                ];
            }

            $detailData = [
                'name' => "WarpPanel " . strtoupper($srvName) . " Web Server ({$channel})",
                'server' => $srvName,
                'channel' => $channel,
                'build_id' => $buildDate,
                'build_date' => $buildDate,
                'build_timestamp' => $now,
                'target' => $targetKey,
                'base_image' => $srvCfg['base_image'],
                'registry' => $registry,
                'primary_tag' => $fullTags[1] ?? $fullTags[0],
                'tags' => $rawTags,
                'full_image_tags' => $fullTags,
                'software_stack' => $stackInfo,
                'features' => $srvCfg['features'] ?? [],
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
            ];

            file_put_contents(
                $this->rootDir . '/' . $detailRelPath,
                json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
            file_put_contents(
                $this->rootDir . '/' . $latestRelPath,
                json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

            $summaryItem = [
                'server' => $srvName,
                'channel' => $channel,
                'build_id' => $buildDate,
                'target' => $targetKey,
                'base_image' => $srvCfg['base_image'],
                'primary_tag' => $fullTags[1] ?? $fullTags[0],
                'floating_tag' => $fullTags[0],
                'tags' => $rawTags,
                'full_image_tags' => $fullTags,
                'details_file' => $detailRelPath,
                'features' => $srvCfg['features'] ?? [],
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
            ];
            $catalog['images']['webservers'][] = $summaryItem;
            if ($isVerified) {
                $simpleList['webservers'][$srvName] = $fullTags;
            }
        }

        // 5. Traefik (Edge Router & Load Balancer)
        if (!empty($matrix['images']['traefik']['versions'])) {
            foreach ($matrix['images']['traefik']['versions'] as $trImg) {
                $ver = $trImg['version'];
                $verKey = str_replace('.', '_', $ver);
                $targetKey = "traefik-v{$verKey}";
                $fullTags = $this->expandTags($trImg['tags'], $registry, 'traefik', $buildDate, $channel);
                $rawTags = $this->expandRawTags($trImg['tags'], $buildDate, $channel);
                $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("traefik:v{$ver}", $verifiedTargets, true);

                $trDir = $channelDir . "/traefik/{$ver}";
                $this->ensureDir($trDir);
                $detailRelPath = "catalog/{$channel}/traefik/{$ver}/{$buildDate}.json";
                $latestRelPath = "catalog/{$channel}/traefik/{$ver}/latest.json";

                $detailData = [
                    'name' => "WarpPanel Traefik v{$ver} Edge Router ({$channel})",
                    'product' => 'Traefik',
                    'channel' => $channel,
                    'version' => $ver,
                    'build_id' => $buildDate,
                    'build_date' => $buildDate,
                    'build_timestamp' => $now,
                    'target' => $targetKey,
                    'base_image' => $trImg['base_image'],
                    'registry' => $registry,
                    'primary_tag' => $fullTags[1] ?? $fullTags[0],
                    'tags' => $rawTags,
                    'full_image_tags' => $fullTags,
                    'software_stack' => [
                        'software' => "Traefik v{$ver} Cloud-Native Ingress & Reverse Proxy",
                        'providers' => ['Docker Provider (auto-discovery)', 'File Provider (dynamic middleware)'],
                        'features' => $trImg['features'] ?? [],
                        'ports' => [80, 443, '443/udp (HTTP/3)', 8080],
                    ],
                    'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                    'verified_at' => $isVerified ? $now : null,
                ];

                file_put_contents(
                    $this->rootDir . '/' . $detailRelPath,
                    json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                );
                file_put_contents(
                    $this->rootDir . '/' . $latestRelPath,
                    json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                );

                $summaryItem = [
                    'version' => $ver,
                    'channel' => $channel,
                    'build_id' => $buildDate,
                    'target' => $targetKey,
                    'base_image' => $trImg['base_image'],
                    'primary_tag' => $fullTags[1] ?? $fullTags[0],
                    'floating_tag' => $fullTags[0],
                    'tags' => $rawTags,
                    'full_image_tags' => $fullTags,
                    'details_file' => $detailRelPath,
                    'features' => $trImg['features'] ?? [],
                    'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                    'verified_at' => $isVerified ? $now : null,
                ];
                $catalog['images']['traefik'][] = $summaryItem;
                if ($isVerified) {
                    $simpleList['traefik'][$ver] = $fullTags;
                }
            }
        }

        // 6. Databases
        if (!empty($matrix['images']['databases'])) {
            foreach ($matrix['images']['databases'] as $dbType => $dbItems) {
                foreach ($dbItems as $dbImg) {
                    $ver = $dbImg['version'];
                    $verKey = str_replace('.', '_', $ver);
                    $targetKey = "{$dbType}-{$verKey}";
                    $fullTags = $this->expandTags($dbImg['tags'], $registry, $dbType, $buildDate, $channel);
                    $rawTags = $this->expandRawTags($dbImg['tags'], $buildDate, $channel);
                    $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("{$dbType}:{$ver}", $verifiedTargets, true);

                    $dbDir = $channelDir . "/databases/{$dbType}-{$ver}";
                    $this->ensureDir($dbDir);
                    $detailRelPath = "catalog/{$channel}/databases/{$dbType}-{$ver}/{$buildDate}.json";
                    $latestRelPath = "catalog/{$channel}/databases/{$dbType}-{$ver}/latest.json";

                    $dbFeatures = [];
                    if ($dbType === 'mysql') {
                        $dbFeatures = [
                            'engine' => 'MySQL Community Server',
                            'version' => $ver,
                            'charset' => 'utf8mb4 (utf8mb4_unicode_ci)',
                            'storage_engine' => 'InnoDB (Buffer Pool 256MB, Barracuda per-table)',
                            'network' => 'skip-name-resolve (Fast container networking)',
                            'default_port' => 3306,
                        ];
                    } elseif ($dbType === 'mariadb') {
                        $dbFeatures = [
                            'engine' => 'MariaDB Server (Alpine Linux)',
                            'version' => $ver,
                            'charset' => 'utf8mb4 (utf8mb4_unicode_ci)',
                            'storage_engine' => 'Aria / InnoDB tuned',
                            'network' => 'skip-name-resolve',
                            'default_port' => 3306,
                        ];
                    } elseif ($dbType === 'postgres') {
                        $dbFeatures = [
                            'engine' => 'PostgreSQL Database Server (Alpine Linux)',
                            'version' => $ver,
                            'default_port' => 5432,
                        ];
                    } elseif ($dbType === 'redis') {
                        $dbFeatures = [
                            'engine' => 'Redis In-Memory Key-Value Data Store (Alpine Linux)',
                            'version' => $ver,
                            'cache_policy' => 'allkeys-lru (Automatic LRU Cache Eviction)',
                            'persistence' => 'AOF (Append-Only File) + RDB snapshots',
                            'default_port' => 6379,
                        ];
                    } elseif ($dbType === 'mongodb') {
                        $dbFeatures = [
                            'engine' => 'MongoDB Document Database',
                            'version' => $ver,
                            'default_port' => 27017,
                        ];
                    }

                    $detailData = [
                        'name' => "WarpPanel " . ucfirst($dbType) . " {$ver} ({$channel})",
                        'type' => $dbType,
                        'channel' => $channel,
                        'version' => $ver,
                        'build_id' => $buildDate,
                        'build_date' => $buildDate,
                        'build_timestamp' => $now,
                        'target' => $targetKey,
                        'base_image' => $dbImg['base_image'],
                        'registry' => $registry,
                        'primary_tag' => $fullTags[1] ?? $fullTags[0],
                        'tags' => $rawTags,
                        'full_image_tags' => $fullTags,
                        'software_stack' => $dbFeatures,
                        'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                        'verified_at' => $isVerified ? $now : null,
                    ];

                    file_put_contents(
                        $this->rootDir . '/' . $detailRelPath,
                        json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                    );
                    file_put_contents(
                        $this->rootDir . '/' . $latestRelPath,
                        json_encode($detailData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                    );

                    $summaryItem = [
                        'type' => $dbType,
                        'channel' => $channel,
                        'version' => $ver,
                        'build_id' => $buildDate,
                        'target' => $targetKey,
                        'base_image' => $dbImg['base_image'],
                        'primary_tag' => $fullTags[1] ?? $fullTags[0],
                        'floating_tag' => $fullTags[0],
                        'tags' => $rawTags,
                        'full_image_tags' => $fullTags,
                        'details_file' => $detailRelPath,
                        'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                        'verified_at' => $isVerified ? $now : null,
                    ];
                    $catalog['images']['databases'][] = $summaryItem;
                    if ($isVerified) {
                        $simpleList['databases']["{$dbType}:{$ver}"] = $fullTags;
                    }
                }
            }
        }

        // Save catalog.json (Master Index)
        file_put_contents(
            $this->catalogJsonFile,
            json_encode($catalog, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        // Save available-images.json (Tags Mapping)
        file_put_contents(
            $this->availableImagesJsonFile,
            json_encode($simpleList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        // Save CATALOG.md
        $this->writeMarkdownCatalog($catalog);

        return $catalog;
    }

    private function expandTags(array $tags, string $registry, string $repoName, string $buildDate, string $channel = 'current'): array
    {
        $expanded = [];
        foreach ($tags as $t) {
            if ($channel === 'dev') {
                $expanded[] = "{$registry}/{$repoName}:{$t}-dev";
                $expanded[] = "{$registry}/{$repoName}:{$t}-dev-{$buildDate}";
            } elseif ($channel === 'stable') {
                $expanded[] = "{$registry}/{$repoName}:{$t}";
                $expanded[] = "{$registry}/{$repoName}:{$t}-stable";
                $expanded[] = "{$registry}/{$repoName}:{$t}-stable-{$buildDate}";
            } else {
                // current (default)
                $expanded[] = "{$registry}/{$repoName}:{$t}-current";
                $expanded[] = "{$registry}/{$repoName}:{$t}-{$buildDate}";
            }
        }
        return array_values(array_unique($expanded));
    }

    private function expandRawTags(array $tags, string $buildDate, string $channel = 'current'): array
    {
        $expanded = [];
        foreach ($tags as $t) {
            if ($channel === 'dev') {
                $expanded[] = "{$t}-dev";
                $expanded[] = "{$t}-dev-{$buildDate}";
            } elseif ($channel === 'stable') {
                $expanded[] = $t;
                $expanded[] = "{$t}-stable";
                $expanded[] = "{$t}-stable-{$buildDate}";
            } else {
                // current (default)
                $expanded[] = "{$t}-current";
                $expanded[] = "{$t}-{$buildDate}";
            }
        }
        return array_values(array_unique($expanded));
    }

    private function buildExtensionsMap(array $configuredExts, string $phpVersion, array $runtimeExts = []): array
    {
        $peclDefaults = [
            'redis' => '6.0.2',
            'imagick' => '3.7.0',
            'igbinary' => '3.2.16',
            'ioncube_loader' => '13.3.0',
            'ionCube Loader' => '13.3.0',
            'xdebug' => str_starts_with($phpVersion, '5.') ? '2.5.5' : (str_starts_with($phpVersion, '7.0') ? '2.7.2' : (str_starts_with($phpVersion, '7.1') ? '2.9.8' : '3.3.2')),
        ];

        $result = [];
        foreach ($configuredExts as $ext) {
            if (!empty($runtimeExts[$ext])) {
                $result[$ext] = (string)$runtimeExts[$ext];
            } elseif (isset($peclDefaults[$ext])) {
                $result[$ext] = $peclDefaults[$ext];
            } else {
                $result[$ext] = $phpVersion;
            }
        }
        foreach ($runtimeExts as $extName => $extVer) {
            if (!isset($result[$extName])) {
                $result[$extName] = (string)$extVer;
            }
        }
        ksort($result);
        return $result;
    }

    private function buildSystemPackagesMap(array $configuredPackages, array $runtimePackages = []): array
    {
        $defaultPkgVersions = [
            'bash' => '5.2.x (Alpine)',
            'curl' => '8.9.x (Alpine)',
            'ca-certificates' => '20240705.x',
            'git' => '2.45.x',
            'unzip' => '6.0.x',
            'zip' => '3.0.x',
            'msmtp' => '1.8.x',
            'shadow' => '4.15.x',
            'tzdata' => '2024a.x',
            'sqlite' => '3.45.x',
            'sqlite3' => '3.45.x',
        ];

        $result = [];
        foreach ($configuredPackages as $pkg) {
            if (!empty($runtimePackages[$pkg])) {
                $result[$pkg] = (string)$runtimePackages[$pkg];
            } elseif (isset($defaultPkgVersions[$pkg])) {
                $result[$pkg] = $defaultPkgVersions[$pkg];
            } else {
                $result[$pkg] = 'installed (Alpine base)';
            }
        }
        foreach ($runtimePackages as $pkgName => $pkgVer) {
            if (!isset($result[$pkgName])) {
                $result[$pkgName] = (string)$pkgVer;
            }
        }
        ksort($result);
        return $result;
    }

    private function ensureDir(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    private function writeMarkdownCatalog(array $catalog): void
    {
        $channel = strtoupper($catalog['channel'] ?? 'CURRENT');
        $md = "# 🚀 WarpPanel Verified Container Images Catalog\n\n";
        $md .= "> **Release Channel:** `{$channel}`  \n";
        $md .= "> **Last Updated:** `{$catalog['updated_at']}`  \n";
        $md .= "> **Active Build ID:** `{$catalog['current_build_id']}`  \n";
        $md .= "> **Primary Registry:** `{$catalog['registry']}`  \n\n";
        $md .= "Central registry and catalog of verified container images for the WarpPanel hosting platform. Each image and release channel (`current`, `stable`, `dev`) has a dedicated software bill of materials (SBOM) in the `catalog/{channel}/` directory detailing installed packages, extensions, and runtime defaults.\n\n";

        // 1. PHP-FPM
        $md .= "## 1. 🐘 PHP-FPM (Alpine Linux)\n\n";
        $md .= "| Version | Build ID | Type | Base Docker Image | Primary Image Tag | Build Specification | Status |\n";
        $md .= "| :--- | :--- | :--- | :--- | :--- | :--- | :--- |\n";
        foreach ($catalog['images']['php_fpm'] as $item) {
            $primaryTag = $item['primary_tag'];
            $statusBadge = ($item['status'] === 'VERIFIED_PASS') ? '✅ **VERIFIED (PASS)**' : '⚡ READY';
            $detailsLink = "[📄 Specification {$item['build_id']}]({$item['details_file']})";
            $md .= "| **PHP {$item['version']}** | `{$item['build_id']}` | `{$item['type']}` | `{$item['base_image']}` | `{$primaryTag}` | {$detailsLink} | {$statusBadge} |\n";
        }

        // 2. FrankenPHP
        $md .= "\n## 2. ⚡ FrankenPHP (All-in-One Caddy + PHP + Worker Mode)\n\n";
        $md .= "| PHP Version | Build ID | Engine / Server | Base Docker Image | Primary Image Tag | Build Specification | Status |\n";
        $md .= "| :--- | :--- | :--- | :--- | :--- | :--- | :--- |\n";
        foreach ($catalog['images']['frankenphp'] as $item) {
            $primaryTag = $item['primary_tag'];
            $statusBadge = ($item['status'] === 'VERIFIED_PASS') ? '✅ **VERIFIED (PASS)**' : '⚡ READY';
            $detailsLink = "[📄 Specification {$item['build_id']}]({$item['details_file']})";
            $md .= "| **PHP {$item['php_version']}** | `{$item['build_id']}` | FrankenPHP 1.x (Caddy v2) | `{$item['base_image']}` | `{$primaryTag}` | {$detailsLink} | {$statusBadge} |\n";
        }

        // 3. Webservers
        $md .= "\n## 3. 🌐 Web Servers (Standalone)\n\n";
        $md .= "| Server | Build ID | Base Docker Image | Features / Protocols | Primary Image Tag | Build Specification | Status |\n";
        $md .= "| :--- | :--- | :--- | :--- | :--- | :--- | :--- |\n";
        foreach ($catalog['images']['webservers'] as $item) {
            $srvName = strtoupper($item['server']);
            $primaryTag = $item['primary_tag'];
            $statusBadge = ($item['status'] === 'VERIFIED_PASS') ? '✅ **VERIFIED (PASS)**' : '⚡ READY';
            $featsStr = implode(', ', array_map(fn($f) => "`{$f}`", $item['features'] ?? []));
            $detailsLink = "[📄 Specification {$item['build_id']}]({$item['details_file']})";
            $md .= "| **{$srvName}** | `{$item['build_id']}` | `{$item['base_image']}` | {$featsStr} | `{$primaryTag}` | {$detailsLink} | {$statusBadge} |\n";
        }

        // 4. Traefik (Edge Router & Load Balancer)
        if (!empty($catalog['images']['traefik'])) {
            $md .= "\n## 4. 🚦 Traefik (Cloud-Native Ingress, Reverse Proxy & Load Balancer)\n\n";
            $md .= "| Version | Build ID | Base Docker Image | Features / Protocols | Primary Image Tag | Build Specification | Status |\n";
            $md .= "| :--- | :--- | :--- | :--- | :--- | :--- | :--- |\n";
            foreach ($catalog['images']['traefik'] as $item) {
                $primaryTag = $item['primary_tag'];
                $statusBadge = ($item['status'] === 'VERIFIED_PASS') ? '✅ **VERIFIED (PASS)**' : '⚡ READY';
                $featsStr = implode(', ', array_map(fn($f) => "`{$f}`", $item['features'] ?? []));
                $detailsLink = "[📄 Specification {$item['build_id']}]({$item['details_file']})";
                $md .= "| **Traefik v{$item['version']}** | `{$item['build_id']}` | `{$item['base_image']}` | {$featsStr} | `{$primaryTag}` | {$detailsLink} | {$statusBadge} |\n";
            }
        }

        // 5. Databases
        if (!empty($catalog['images']['databases'])) {
            $md .= "\n## 5. 🗄️ Network Databases & Caching Engines\n\n";
            $md .= "| Database / Engine | Version | Build ID | Base Docker Image | Primary Image Tag | Build Specification | Status |\n";
            $md .= "| :--- | :--- | :--- | :--- | :--- | :--- | :--- |\n";
            foreach ($catalog['images']['databases'] as $item) {
                $dbName = ucfirst($item['type']);
                $primaryTag = $item['primary_tag'];
                $statusBadge = ($item['status'] === 'VERIFIED_PASS') ? '✅ **VERIFIED (PASS)**' : '⚡ READY';
                $detailsLink = "[📄 Specification {$item['build_id']}]({$item['details_file']})";
                $md .= "| **{$dbName}** | `{$item['version']}` | `{$item['build_id']}` | `{$item['base_image']}` | `{$primaryTag}` | {$detailsLink} | {$statusBadge} |\n";
            }
        }

        $md .= "\n---\n*Detailed software bill of materials and build specifications are located in the `catalog/` directory.*\n";

        file_put_contents($this->catalogMdFile, $md);
    }

    public function promoteStableIfChanged(): array
    {
        $currentDir = $this->catalogDetailsDir . '/current';
        $stableDir = $this->catalogDetailsDir . '/stable';
        $this->ensureDir($stableDir);

        $changedImages = [];
        $categories = ['php-fpm', 'frankenphp', 'webservers', 'traefik', 'databases'];

        foreach ($categories as $cat) {
            $catCurrent = $currentDir . '/' . $cat;
            if (!is_dir($catCurrent)) {
                continue;
            }
            $items = scandir($catCurrent);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') {
                    continue;
                }
                $currentLatestFile = $catCurrent . '/' . $item . '/latest.json';
                if (!file_exists($currentLatestFile)) {
                    continue;
                }
                $currentData = json_decode(file_get_contents($currentLatestFile), true);
                if (!$currentData) {
                    continue;
                }

                $catStable = $stableDir . '/' . $cat . '/' . $item;
                $this->ensureDir($catStable);
                $stableLatestFile = $catStable . '/latest.json';

                $shouldPromote = false;
                $reasons = [];

                if (!file_exists($stableLatestFile)) {
                    $shouldPromote = true;
                    $reasons[] = 'Initial stable release (new target)';
                } else {
                    $stableData = json_decode(file_get_contents($stableLatestFile), true);

                    // 1. Check Exact Software / PHP Version
                    if (($currentData['exact_php_version'] ?? '') !== ($stableData['exact_php_version'] ?? '')) {
                        $shouldPromote = true;
                        $reasons[] = "PHP Version changed: " . ($stableData['exact_php_version'] ?? 'unknown') . " -> " . ($currentData['exact_php_version'] ?? 'unknown');
                    }

                    // 2. Check PHP Extensions
                    $currExts = $currentData['php_extensions'] ?? [];
                    $stabExts = $stableData['php_extensions'] ?? [];
                    if ($currExts !== $stabExts) {
                        $shouldPromote = true;
                        $diff = array_diff_assoc($currExts, $stabExts);
                        $reasons[] = "PHP Extensions updated: " . implode(', ', array_keys($diff));
                    }

                    // 3. Check System Packages
                    $currPkgs = $currentData['software_stack']['system_packages'] ?? [];
                    $stabPkgs = $stableData['software_stack']['system_packages'] ?? [];
                    if ($currPkgs !== $stabPkgs) {
                        $shouldPromote = true;
                        $diff = array_diff_assoc((array)$currPkgs, (array)$stabPkgs);
                        $reasons[] = "System packages updated: " . implode(', ', array_keys($diff));
                    }

                    // 4. Check Runtime Defaults & PHP INI
                    $currDefaults = $currentData['runtime_defaults'] ?? [];
                    $stabDefaults = $stableData['runtime_defaults'] ?? [];
                    if ($currDefaults !== $stabDefaults) {
                        $shouldPromote = true;
                        $reasons[] = "Runtime defaults / php_ini settings modified";
                    }
                }

                if ($shouldPromote) {
                    $buildDate = $currentData['build_id'] ?? date('Ymd');
                    $now = date('c');

                    // Clone current data for stable
                    $stableData = $currentData;
                    $stableData['name'] = str_replace('(current)', '(stable)', $stableData['name']);
                    $stableData['channel'] = 'stable';
                    $stableData['build_date'] = $buildDate;
                    $stableData['build_timestamp'] = $now;

                    // Determine exact image repository name
                    $repoName = match($cat) {
                        'php-fpm' => 'php',
                        'frankenphp' => 'frankenphp',
                        'webservers' => $item,
                        'traefik' => 'traefik',
                        'databases' => $currentData['type'] ?? explode('-', $item)[0],
                        default => $item
                    };

                    $cleanTags = array_filter(
                        $currentData['tags'],
                        fn($t) => !str_ends_with($t, '-current') && !preg_match('/-\d{8}$/', $t)
                    );

                    // Convert tags to stable tags
                    $stableData['tags'] = $this->expandRawTags(
                        $cleanTags,
                        $buildDate,
                        'stable'
                    );
                    $stableData['full_image_tags'] = $this->expandTags(
                        $cleanTags,
                        $currentData['registry'] ?? 'ghcr.io/warppanel',
                        $repoName,
                        $buildDate,
                        'stable'
                    );
                    $stableData['primary_tag'] = $stableData['full_image_tags'][1] ?? $stableData['full_image_tags'][0];

                    file_put_contents(
                        $catStable . "/{$buildDate}.json",
                        json_encode($stableData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                    );
                    file_put_contents(
                        $stableLatestFile,
                        json_encode($stableData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                    );

                    $changedImages[] = [
                        'category' => $cat,
                        'item' => $item,
                        'reasons' => $reasons,
                        'source_image' => $currentData['primary_tag'] ?? '',
                        'stable_tags' => $stableData['full_image_tags'] ?? [],
                    ];
                }
            }
        }

        return $changedImages;
    }

    public function cleanExpiredCurrentBuilds(int $retentionDays = 30): array
    {
        $currentDir = $this->catalogDetailsDir . '/current';
        if (!is_dir($currentDir)) {
            return [];
        }

        $cutoffTimestamp = strtotime("-{$retentionDays} days");
        $deletedFiles = [];
        $categories = ['php-fpm', 'frankenphp', 'webservers', 'traefik', 'databases'];

        foreach ($categories as $cat) {
            $catDir = $currentDir . '/' . $cat;
            if (!is_dir($catDir)) {
                continue;
            }
            $items = scandir($catDir);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') {
                    continue;
                }
                $itemDir = $catDir . '/' . $item;
                if (!is_dir($itemDir)) {
                    continue;
                }
                $files = scandir($itemDir);
                foreach ($files as $file) {
                    // Match dated files like 20260830.json (skip latest.json)
                    if (preg_match('/^(\d{4})(\d{2})(\d{2})\.json$/', $file, $m)) {
                        $fileDateStr = "{$m[1]}-{$m[2]}-{$m[3]}";
                        $fileTimestamp = strtotime($fileDateStr);
                        if ($fileTimestamp !== false && $fileTimestamp < $cutoffTimestamp) {
                            $filePath = $itemDir . '/' . $file;
                            unlink($filePath);
                            $deletedFiles[] = "catalog/current/{$cat}/{$item}/{$file}";
                        }
                    }
                }
            }
        }

        // Clean available_builds in catalog.json
        if (file_exists($this->catalogJsonFile)) {
            $catalog = json_decode(file_get_contents($this->catalogJsonFile), true);
            if (!empty($catalog['available_builds'])) {
                $catalog['available_builds'] = array_values(array_filter(
                    $catalog['available_builds'],
                    function ($build) use ($cutoffTimestamp) {
                        if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $build, $m)) {
                            $ts = strtotime("{$m[1]}-{$m[2]}-{$m[3]}");
                            return $ts >= $cutoffTimestamp;
                        }
                        return true;
                    }
                ));
                file_put_contents($this->catalogJsonFile, json_encode($catalog, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        }

        return $deletedFiles;
    }
}

