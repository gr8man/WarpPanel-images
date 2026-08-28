<?php

declare(strict_types=1);

namespace WarpPanel\Images;

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Generator
{
    private string $rootDir;
    private string $templatesDir;
    private string $buildDir;
    private string $matrixFile;
    private string $bakeFile;
    private Environment $twig;
    private CatalogManager $catalogManager;

    public function __construct(string $rootDir)
    {
        $this->rootDir = rtrim($rootDir, '/');
        $this->templatesDir = $this->rootDir . '/templates';
        $this->buildDir = $this->rootDir . '/build';
        $this->matrixFile = $this->rootDir . '/matrix.yaml';
        $this->bakeFile = $this->rootDir . '/docker-bake.hcl';
        $this->catalogManager = new CatalogManager($this->rootDir);

        $loader = new FilesystemLoader($this->templatesDir);
        $this->twig = new Environment($loader, [
            'autoescape' => false,
            'strict_variables' => false,
        ]);
    }

    public function run(bool $catalogOnly = false, array $verifiedImages = []): void
    {
        $matrix = Yaml::parseFile($this->matrixFile);
        $envRegistry = getenv('IMAGE_REGISTRY');
        $registry = ($envRegistry !== false && $envRegistry !== '') ? $envRegistry : ($matrix['registry'] ?? 'ghcr.io/warppanel');
        $bakeTargets = [];

        if (!$catalogOnly) {
            $this->cleanDir($this->buildDir);
        }

        // 1. PHP-FPM Modern (8.0 - 8.5)
        foreach ($matrix['images']['php_fpm']['modern'] as $img) {
            $ver = $img['version'];
            $targetDir = $this->buildDir . "/php-fpm/{$ver}";
            $tags = array_map(fn($t) => "{$registry}/php:{$t}", $img['tags']);
            $targetName = "php-fpm-" . str_replace('.', '_', $ver);

            if (!$catalogOnly) {
                $this->ensureDir($targetDir);
                $content = $this->twig->render('php-fpm/Dockerfile.alpine-modern.j2', [
                    'image' => $img,
                    'matrix' => $matrix,
                ]);
                file_put_contents("{$targetDir}/Dockerfile", $content);
                copy($this->templatesDir . '/common/docker-entrypoint.sh', "{$targetDir}/docker-entrypoint.sh");
            }

            $bakeTargets[$targetName] = [
                'context' => "./build/php-fpm/{$ver}",
                'dockerfile' => 'Dockerfile',
                'tags' => $tags,
                'platforms' => ['linux/amd64', 'linux/arm64'],
            ];
        }

        // 2. PHP-FPM Legacy (5.6 - 7.4)
        foreach ($matrix['images']['php_fpm']['legacy'] as $img) {
            $ver = $img['version'];
            $targetDir = $this->buildDir . "/php-fpm/{$ver}";
            $tags = array_map(fn($t) => "{$registry}/php:{$t}", $img['tags']);
            $targetName = "php-fpm-" . str_replace('.', '_', $ver);

            if (!$catalogOnly) {
                $this->ensureDir($targetDir);
                $content = $this->twig->render('php-fpm/Dockerfile.alpine-legacy.j2', [
                    'image' => $img,
                    'matrix' => $matrix,
                ]);
                file_put_contents("{$targetDir}/Dockerfile", $content);
                copy($this->templatesDir . '/common/docker-entrypoint.sh', "{$targetDir}/docker-entrypoint.sh");
            }

            $bakeTargets[$targetName] = [
                'context' => "./build/php-fpm/{$ver}",
                'dockerfile' => 'Dockerfile',
                'tags' => $tags,
                'platforms' => ['linux/amd64'],
            ];
        }

        // 3. FrankenPHP
        foreach ($matrix['images']['frankenphp']['versions'] as $img) {
            $ver = $img['php_version'];
            $targetDir = $this->buildDir . "/frankenphp/{$ver}";
            $tags = array_map(fn($t) => "{$registry}/frankenphp:{$t}", $img['tags']);
            $targetName = "frankenphp-" . str_replace('.', '_', $ver);

            if (!$catalogOnly) {
                $this->ensureDir($targetDir);
                $content = $this->twig->render('frankenphp/Dockerfile.j2', [
                    'image' => $img,
                    'matrix' => $matrix,
                ]);
                file_put_contents("{$targetDir}/Dockerfile", $content);
                copy($this->templatesDir . '/frankenphp/Caddyfile', "{$targetDir}/Caddyfile");
                copy($this->templatesDir . '/frankenphp/docker-entrypoint-frankenphp.sh', "{$targetDir}/docker-entrypoint-frankenphp.sh");
            }

            $bakeTargets[$targetName] = [
                'context' => "./build/frankenphp/{$ver}",
                'dockerfile' => 'Dockerfile',
                'tags' => $tags,
                'platforms' => ['linux/amd64', 'linux/arm64'],
            ];
        }

        // 4. Webservers
        $webservers = $matrix['images']['webservers'];

        // Nginx
        $nginxDir = $this->buildDir . '/nginx';
        $nginxTags = array_map(fn($t) => "{$registry}/nginx:{$t}", $webservers['nginx']['tags']);
        if (!$catalogOnly) {
            $this->ensureDir($nginxDir);
            file_put_contents(
                "{$nginxDir}/Dockerfile",
                $this->twig->render('nginx/Dockerfile.j2', ['image' => $webservers['nginx'], 'matrix' => $matrix])
            );
            foreach (['nginx.conf', 'default.conf.template', 'waf-rules.conf', 'docker-entrypoint-nginx.sh'] as $f) {
                copy($this->templatesDir . "/nginx/{$f}", "{$nginxDir}/{$f}");
            }
            copy($this->templatesDir . '/common/cloudflare-ips.txt', "{$nginxDir}/cloudflare-ips.txt");
        }
        $bakeTargets['nginx'] = [
            'context' => './build/nginx',
            'dockerfile' => 'Dockerfile',
            'tags' => $nginxTags,
            'platforms' => ['linux/amd64', 'linux/arm64'],
        ];

        // Apache
        $apacheDir = $this->buildDir . '/apache';
        $apacheTags = array_map(fn($t) => "{$registry}/apache:{$t}", $webservers['apache']['tags']);
        if (!$catalogOnly) {
            $this->ensureDir($apacheDir);
            file_put_contents(
                "{$apacheDir}/Dockerfile",
                $this->twig->render('apache/Dockerfile.j2', ['image' => $webservers['apache'], 'matrix' => $matrix])
            );
            foreach (['httpd.conf', 'vhost.conf.template', 'waf-rules.conf', 'docker-entrypoint-apache.sh'] as $f) {
                copy($this->templatesDir . "/apache/{$f}", "{$apacheDir}/{$f}");
            }
            copy($this->templatesDir . '/common/cloudflare-ips.txt', "{$apacheDir}/cloudflare-ips.txt");
        }
        $bakeTargets['apache'] = [
            'context' => './build/apache',
            'dockerfile' => 'Dockerfile',
            'tags' => $apacheTags,
            'platforms' => ['linux/amd64', 'linux/arm64'],
        ];

        // OpenLiteSpeed
        $olsDir = $this->buildDir . '/openlitespeed';
        $olsTags = array_map(fn($t) => "{$registry}/openlitespeed:{$t}", $webservers['openlitespeed']['tags']);
        if (!$catalogOnly) {
            $this->ensureDir($olsDir);
            file_put_contents(
                "{$olsDir}/Dockerfile",
                $this->twig->render('openlitespeed/Dockerfile.j2', ['image' => $webservers['openlitespeed'], 'matrix' => $matrix])
            );
        }
        $bakeTargets['openlitespeed'] = [
            'context' => './build/openlitespeed',
            'dockerfile' => 'Dockerfile',
            'tags' => $olsTags,
            'platforms' => ['linux/amd64'],
        ];

        if (!$catalogOnly) {
            $this->writeBakeFile($bakeTargets);
        }

        $this->catalogManager->generateCatalog($verifiedImages);
    }

    private function writeBakeFile(array $targets): void
    {
        $targetNames = array_keys($targets);
        $phpTargets = array_values(array_filter($targetNames, fn($t) => str_starts_with($t, 'php-fpm')));
        $frankenTargets = array_values(array_filter($targetNames, fn($t) => str_starts_with($t, 'frankenphp')));

        $content = "group \"default\" {\n    targets = " . json_encode($targetNames) . "\n}\n\n";
        $content .= "group \"php\" {\n    targets = " . json_encode($phpTargets) . "\n}\n\n";
        $content .= "group \"frankenphp\" {\n    targets = " . json_encode($frankenTargets) . "\n}\n\n";
        $content .= "group \"webservers\" {\n    targets = [\"nginx\", \"apache\", \"openlitespeed\"]\n}\n\n";

        foreach ($targets as $name => $cfg) {
            $content .= "target \"{$name}\" {\n";
            $content .= "    context = \"{$cfg['context']}\"\n";
            $content .= "    dockerfile = \"{$cfg['dockerfile']}\"\n";
            $content .= "    tags = " . json_encode($cfg['tags']) . "\n";
            $content .= "    platforms = " . json_encode($cfg['platforms']) . "\n";
            $content .= "}\n\n";
        }

        file_put_contents($this->bakeFile, $content);
    }

    private function ensureDir(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    private function cleanDir(string $path): void
    {
        if (is_dir($path)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
        } else {
            $this->ensureDir($path);
        }
    }
}
