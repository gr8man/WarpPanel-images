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

    public function __construct(string $rootDir)
    {
        $this->rootDir = rtrim($rootDir, '/');
        $this->matrixFile = $this->rootDir . '/matrix.yaml';
        $this->catalogJsonFile = $this->rootDir . '/catalog.json';
        $this->catalogMdFile = $this->rootDir . '/CATALOG.md';
        $this->availableImagesJsonFile = $this->rootDir . '/available-images.json';
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
        if (is_dir($dir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && str_starts_with($file->getFilename(), 'verification-') && $file->getExtension() === 'json') {
                    $json = json_decode(file_get_contents($file->getRealPath()), true);
                    if (!empty($json['target']) && ($json['status'] ?? '') === 'VERIFIED_PASS') {
                        $verifiedTargets[] = $json['target'];
                        echo "  [+] Found verified target: {$json['target']} (from {$file->getFilename()})\n";
                    }
                }
            }
        }

        $verifiedTargets = array_unique($verifiedTargets);
        return $this->generateCatalog($verifiedTargets);
    }

    public function generateCatalog(array $verifiedTargets = []): array
    {
        $matrix = Yaml::parseFile($this->matrixFile);
        $envRegistry = getenv('IMAGE_REGISTRY');
        $registry = ($envRegistry !== false && $envRegistry !== '') ? $envRegistry : ($matrix['registry'] ?? 'ghcr.io/warppanel');
        $now = date('c');

        $totalDatabases = 0;
        if (!empty($matrix['images']['databases'])) {
            foreach ($matrix['images']['databases'] as $dbItems) {
                $totalDatabases += count($dbItems);
            }
        }

        $catalog = [
            'version' => '1.0',
            'generated_at' => $now,
            'registry' => $registry,
            'summary' => [
                'total_php_versions' => count($matrix['images']['php_fpm']['legacy']) + count($matrix['images']['php_fpm']['modern']),
                'total_frankenphp_versions' => count($matrix['images']['frankenphp']['versions']),
                'total_webservers' => count($matrix['images']['webservers']),
                'total_databases' => $totalDatabases,
                'verified_count' => count($verifiedTargets),
            ],
            'images' => [
                'php_fpm' => [],
                'frankenphp' => [],
                'webservers' => [],
                'databases' => [],
            ],
        ];

        $simpleList = [
            'registry' => $registry,
            'updated_at' => $now,
            'php' => [],
            'frankenphp' => [],
            'webservers' => [],
            'databases' => [],
        ];

        // 1. PHP-FPM Modern
        foreach ($matrix['images']['php_fpm']['modern'] as $img) {
            $ver = $img['version'];
            $targetKey = 'php-fpm-' . str_replace('.', '_', $ver);
            $fullTags = array_map(fn($t) => "{$registry}/php:{$t}", $img['tags']);
            $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("php:{$ver}", $verifiedTargets, true);

            $item = [
                'version' => $ver,
                'target' => $targetKey,
                'type' => 'modern',
                'base_image' => $img['base_image'],
                'tags' => $img['tags'],
                'full_image_tags' => $fullTags,
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
                'php_extensions' => $matrix['defaults']['php_extensions'] ?? [],
            ];
            $catalog['images']['php_fpm'][] = $item;
            if ($isVerified) {
                $simpleList['php'][$ver] = $fullTags;
            }
        }

        // 2. PHP-FPM Legacy
        foreach ($matrix['images']['php_fpm']['legacy'] as $img) {
            $ver = $img['version'];
            $targetKey = 'php-fpm-' . str_replace('.', '_', $ver);
            $fullTags = array_map(fn($t) => "{$registry}/php:{$t}", $img['tags']);
            $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("php:{$ver}", $verifiedTargets, true);

            $item = [
                'version' => $ver,
                'target' => $targetKey,
                'type' => 'legacy',
                'base_image' => $img['base_image'],
                'tags' => $img['tags'],
                'full_image_tags' => $fullTags,
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
                'php_extensions' => ['pdo_mysql', 'pdo_sqlite', 'sqlite3', 'mysqli', 'gd', 'opcache', 'curl', 'zip', 'intl', 'mbstring'],
            ];
            $catalog['images']['php_fpm'][] = $item;
            if ($isVerified) {
                $simpleList['php'][$ver] = $fullTags;
            }
        }

        // 3. FrankenPHP
        foreach ($matrix['images']['frankenphp']['versions'] as $img) {
            $ver = $img['php_version'];
            $targetKey = 'frankenphp-' . str_replace('.', '_', $ver);
            $fullTags = array_map(fn($t) => "{$registry}/frankenphp:{$t}", $img['tags']);
            $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("frankenphp:{$ver}", $verifiedTargets, true);

            $item = [
                'php_version' => $ver,
                'target' => $targetKey,
                'base_image' => $img['base_image'],
                'tags' => $img['tags'],
                'full_image_tags' => $fullTags,
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
                'features' => ['caddy', 'worker_mode', 'http3', 'https_auto'],
            ];
            $catalog['images']['frankenphp'][] = $item;
            if ($isVerified) {
                $simpleList['frankenphp'][$ver] = $fullTags;
            }
        }

        // 4. Webservers
        foreach ($matrix['images']['webservers'] as $srvName => $srvCfg) {
            $targetKey = $srvName;
            $fullTags = array_map(fn($t) => "{$registry}/{$srvName}:{$t}", $srvCfg['tags']);
            $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("webserver:{$srvName}", $verifiedTargets, true);

            $item = [
                'server' => $srvName,
                'target' => $targetKey,
                'base_image' => $srvCfg['base_image'],
                'tags' => $srvCfg['tags'],
                'full_image_tags' => $fullTags,
                'features' => $srvCfg['features'] ?? [],
                'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                'verified_at' => $isVerified ? $now : null,
            ];
            $catalog['images']['webservers'][] = $item;
            if ($isVerified) {
                $simpleList['webservers'][$srvName] = $fullTags;
            }
        }

        // 5. Databases
        if (!empty($matrix['images']['databases'])) {
            foreach ($matrix['images']['databases'] as $dbType => $items) {
                foreach ($items as $dbImg) {
                    $ver = $dbImg['version'];
                    $verKey = str_replace('.', '_', $ver);
                    $targetKey = "{$dbType}-{$verKey}";
                    $fullTags = array_map(fn($t) => "{$registry}/{$dbType}:{$t}", $dbImg['tags']);
                    $isVerified = in_array($targetKey, $verifiedTargets, true) || in_array("{$dbType}:{$ver}", $verifiedTargets, true);

                    $item = [
                        'type' => $dbType,
                        'version' => $ver,
                        'target' => $targetKey,
                        'base_image' => $dbImg['base_image'],
                        'tags' => $dbImg['tags'],
                        'full_image_tags' => $fullTags,
                        'status' => $isVerified ? 'VERIFIED_PASS' : 'READY',
                        'verified_at' => $isVerified ? $now : null,
                    ];
                    $catalog['images']['databases'][] = $item;
                    if ($isVerified) {
                        $simpleList['databases']["{$dbType}:{$ver}"] = $fullTags;
                    }
                }
            }
        }

        // Save catalog.json
        file_put_contents(
            $this->catalogJsonFile,
            json_encode($catalog, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        // Save available-images.json
        file_put_contents(
            $this->availableImagesJsonFile,
            json_encode($simpleList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        // Save CATALOG.md
        $this->writeMarkdownCatalog($catalog);

        return $catalog;
    }

    private function writeMarkdownCatalog(array $catalog): void
    {
        $md = "# Katalog Sprawdzonych Obrazów WarpPanel\n\n";
        $md .= "> **Ostatnia aktualizacja:** `{$catalog['generated_at']}`\n";
        $md .= "> **Rejestr:** `{$catalog['registry']}`\n\n";
        $md .= "Automatycznie generowana lista przetestowanych, zweryfikowanych i gotowych do użycia obrazów dla panelu hostingowego WarpPanel.\n\n";

        $md .= "## 1. PHP-FPM (Alpine Linux)\n\n";
        $md .= "| Wersja | Typ | Baza Docker | Dostępne Tagi | Główny Tag Rejestru | Status Weryfikacji |\n";
        $md .= "| :--- | :--- | :--- | :--- | :--- | :--- |\n";
        foreach ($catalog['images']['php_fpm'] as $item) {
            $tagsStr = implode(', ', array_map(fn($t) => "`{$t}`", $item['tags']));
            $primaryTag = $item['full_image_tags'][0];
            $statusBadge = ($item['status'] === 'VERIFIED_PASS') ? '✅ **VERIFIED (PASS)**' : '⚡ READY';
            $md .= "| **PHP {$item['version']}** | `{$item['type']}` | `{$item['base_image']}` | {$tagsStr} | `{$primaryTag}` | {$statusBadge} |\n";
        }

        $md .= "\n## 2. FrankenPHP (Caddy + PHP Runtime & Worker Mode)\n\n";
        $md .= "| Wersja PHP | Baza Docker | Dostępne Tagi | Główny Tag Rejestru | Status Weryfikacji |\n";
        $md .= "| :--- | :--- | :--- | :--- | :--- | :--- |\n";
        foreach ($catalog['images']['frankenphp'] as $item) {
            $tagsStr = implode(', ', array_map(fn($t) => "`{$t}`", $item['tags']));
            $primaryTag = $item['full_image_tags'][0];
            $statusBadge = ($item['status'] === 'VERIFIED_PASS') ? '✅ **VERIFIED (PASS)**' : '⚡ READY';
            $md .= "| **FrankenPHP (PHP {$item['php_version']})** | `{$item['base_image']}` | {$tagsStr} | `{$primaryTag}` | {$statusBadge} |\n";
        }

        $md .= "\n## 3. Webserwery Standalone\n\n";
        $md .= "| Serwer | Baza Docker | Kluczowe Moduły / Cechy | Główny Tag Rejestru | Status Weryfikacji |\n";
        $md .= "| :--- | :--- | :--- | :--- | :--- | :--- |\n";
        foreach ($catalog['images']['webservers'] as $item) {
            $featsStr = implode(', ', array_map(fn($f) => "`{$f}`", $item['features'] ?? []));
            $primaryTag = $item['full_image_tags'][0];
            $srvName = strtoupper($item['server']);
            $statusBadge = ($item['status'] === 'VERIFIED_PASS') ? '✅ **VERIFIED (PASS)**' : '⚡ READY';
            $md .= "| **{$srvName}** | `{$item['base_image']}` | {$featsStr} | `{$primaryTag}` | {$statusBadge} |\n";
        }

        if (!empty($catalog['images']['databases'])) {
            $md .= "\n## 4. Silniki Baz Danych\n\n";
            $md .= "| Silnik | Wersja | Baza Docker | Dostępne Tagi | Główny Tag Rejestru | Status Weryfikacji |\n";
            $md .= "| :--- | :--- | :--- | :--- | :--- | :--- | :--- |\n";
            foreach ($catalog['images']['databases'] as $item) {
                $tagsStr = implode(', ', array_map(fn($t) => "`{$t}`", $item['tags']));
                $primaryTag = $item['full_image_tags'][0];
                $dbName = ucfirst($item['type']);
                $statusBadge = ($item['status'] === 'VERIFIED_PASS') ? '✅ **VERIFIED (PASS)**' : '⚡ READY';
                $md .= "| **{$dbName}** | `{$item['version']}` | `{$item['base_image']}` | {$tagsStr} | `{$primaryTag}` | {$statusBadge} |\n";
            }
        }

        file_put_contents($this->catalogMdFile, $md);
    }
}
