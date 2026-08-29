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
        $buildDate = getenv('BUILD_DATE') ?: date('Ymd');
        $channel = getenv('BUILD_CHANNEL') ?: 'current';
        $bakeTargets = [];

        if (!$catalogOnly) {
            $this->cleanDir($this->buildDir);
        }

        // 1. PHP-FPM Modern (8.0 - 8.5)
        foreach ($matrix['images']['php_fpm']['modern'] as $img) {
            $ver = $img['version'];
            $targetDir = $this->buildDir . "/php-fpm/{$ver}";
            $tags = $this->expandTags($img['tags'], $registry, 'php', $buildDate, $channel);
            $targetName = "php-fpm-" . str_replace('.', '_', $ver);

            if (!$catalogOnly) {
                $this->ensureDir($targetDir);
                $content = $this->twig->render('php-fpm/Dockerfile.alpine-modern.j2', [
                    'image' => $img,
                    'matrix' => $matrix,
                    'build_date' => $buildDate,
                    'channel' => $channel,
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
            $tags = $this->expandTags($img['tags'], $registry, 'php', $buildDate, $channel);
            $targetName = "php-fpm-" . str_replace('.', '_', $ver);

            if (!$catalogOnly) {
                $this->ensureDir($targetDir);
                $content = $this->twig->render('php-fpm/Dockerfile.alpine-legacy.j2', [
                    'image' => $img,
                    'matrix' => $matrix,
                    'build_date' => $buildDate,
                    'channel' => $channel,
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
            $tags = $this->expandTags($img['tags'], $registry, 'frankenphp', $buildDate, $channel);
            $targetName = "frankenphp-" . str_replace('.', '_', $ver);

            if (!$catalogOnly) {
                $this->ensureDir($targetDir);
                $content = $this->twig->render('frankenphp/Dockerfile.j2', [
                    'image' => $img,
                    'matrix' => $matrix,
                    'build_date' => $buildDate,
                    'channel' => $channel,
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
        $nginxTags = $this->expandTags($webservers['nginx']['tags'], $registry, 'nginx', $buildDate, $channel);
        if (!$catalogOnly) {
            $this->ensureDir($nginxDir);
            file_put_contents(
                "{$nginxDir}/Dockerfile",
                $this->twig->render('nginx/Dockerfile.j2', ['image' => $webservers['nginx'], 'matrix' => $matrix, 'build_date' => $buildDate, 'channel' => $channel])
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
        $apacheTags = $this->expandTags($webservers['apache']['tags'], $registry, 'apache', $buildDate, $channel);
        if (!$catalogOnly) {
            $this->ensureDir($apacheDir);
            file_put_contents(
                "{$apacheDir}/Dockerfile",
                $this->twig->render('apache/Dockerfile.j2', ['image' => $webservers['apache'], 'matrix' => $matrix, 'build_date' => $buildDate, 'channel' => $channel])
            );
            foreach (['httpd.conf', 'vhost.conf.template', 'waf-rules.conf', 'docker-entrypoint-apache.sh'] as $f) {
                if (file_exists($this->templatesDir . "/apache/{$f}")) {
                    copy($this->templatesDir . "/apache/{$f}", "{$apacheDir}/{$f}");
                }
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
        $olsTags = $this->expandTags($webservers['openlitespeed']['tags'], $registry, 'openlitespeed', $buildDate, $channel);
        if (!$catalogOnly) {
            $this->ensureDir($olsDir);
            file_put_contents(
                "{$olsDir}/Dockerfile",
                $this->twig->render('openlitespeed/Dockerfile.j2', ['image' => $webservers['openlitespeed'], 'matrix' => $matrix, 'build_date' => $buildDate, 'channel' => $channel])
            );
            foreach (['httpd_config.conf', 'vhost.conf', 'docker-entrypoint-ols.sh'] as $f) {
                if (file_exists($this->templatesDir . "/openlitespeed/{$f}")) {
                    copy($this->templatesDir . "/openlitespeed/{$f}", "{$olsDir}/{$f}");
                }
            }
        }
        $bakeTargets['openlitespeed'] = [
            'context' => './build/openlitespeed',
            'dockerfile' => 'Dockerfile',
            'tags' => $olsTags,
            'platforms' => ['linux/amd64'],
        ];

        // Caddy (Standalone)
        $caddyDir = $this->buildDir . '/caddy';
        $caddyTags = $this->expandTags($webservers['caddy']['tags'], $registry, 'caddy', $buildDate, $channel);
        if (!$catalogOnly) {
            $this->ensureDir($caddyDir);
            file_put_contents(
                "{$caddyDir}/Dockerfile",
                $this->twig->render('caddy/Dockerfile.j2', ['image' => $webservers['caddy'], 'matrix' => $matrix, 'build_date' => $buildDate, 'channel' => $channel])
            );
            foreach (['Caddyfile', 'docker-entrypoint-caddy.sh'] as $f) {
                if (file_exists($this->templatesDir . "/caddy/{$f}")) {
                    copy($this->templatesDir . "/caddy/{$f}", "{$caddyDir}/{$f}");
                }
            }
        }
        $bakeTargets['caddy'] = [
            'context' => './build/caddy',
            'dockerfile' => 'Dockerfile',
            'tags' => $caddyTags,
            'platforms' => ['linux/amd64', 'linux/arm64'],
        ];

        // Lighttpd
        $lighttpdDir = $this->buildDir . '/lighttpd';
        $lighttpdTags = $this->expandTags($webservers['lighttpd']['tags'], $registry, 'lighttpd', $buildDate, $channel);
        if (!$catalogOnly) {
            $this->ensureDir($lighttpdDir);
            file_put_contents(
                "{$lighttpdDir}/Dockerfile",
                $this->twig->render('lighttpd/Dockerfile.j2', ['image' => $webservers['lighttpd'], 'matrix' => $matrix, 'build_date' => $buildDate, 'channel' => $channel])
            );
            foreach (['lighttpd.conf', 'docker-entrypoint-lighttpd.sh'] as $f) {
                if (file_exists($this->templatesDir . "/lighttpd/{$f}")) {
                    copy($this->templatesDir . "/lighttpd/{$f}", "{$lighttpdDir}/{$f}");
                }
            }
        }
        $bakeTargets['lighttpd'] = [
            'context' => './build/lighttpd',
            'dockerfile' => 'Dockerfile',
            'tags' => $lighttpdTags,
            'platforms' => ['linux/amd64', 'linux/arm64'],
        ];

        // 5. Traefik (Edge Router & Ingress)
        if (!empty($matrix['images']['traefik']['versions'])) {
            foreach ($matrix['images']['traefik']['versions'] as $trImg) {
                $ver = $trImg['version'];
                $verKey = str_replace('.', '_', $ver);
                $targetName = "traefik-v" . $verKey;
                $targetDir = $this->buildDir . "/traefik/{$ver}";
                $tags = $this->expandTags($trImg['tags'], $registry, 'traefik', $buildDate, $channel);

                if (!$catalogOnly) {
                    $this->ensureDir($targetDir);
                    file_put_contents(
                        "{$targetDir}/Dockerfile",
                        $this->twig->render('traefik/Dockerfile.j2', [
                            'image' => $trImg,
                            'matrix' => $matrix,
                            'build_date' => $buildDate,
                            'channel' => $channel,
                        ])
                    );
                    foreach (['traefik.yml', 'dynamic_conf.yml'] as $f) {
                        if (file_exists($this->templatesDir . "/traefik/{$f}")) {
                            copy($this->templatesDir . "/traefik/{$f}", "{$targetDir}/{$f}");
                        }
                    }
                }

                $bakeTargets[$targetName] = [
                    'context' => "./build/traefik/{$ver}",
                    'dockerfile' => 'Dockerfile',
                    'tags' => $tags,
                    'platforms' => ['linux/amd64', 'linux/arm64'],
                ];
            }
        }

        // 6. Databases
        if (!empty($matrix['images']['databases'])) {
            foreach ($matrix['images']['databases'] as $dbType => $dbItems) {
                foreach ($dbItems as $dbImg) {
                    $ver = $dbImg['version'];
                    $verKey = str_replace('.', '_', $ver);
                    $targetName = "{$dbType}-{$verKey}";
                    $targetDir = $this->buildDir . "/databases/{$dbType}/{$ver}";
                    $tags = $this->expandTags($dbImg['tags'], $registry, $dbType, $buildDate, $channel);

                    if (!$catalogOnly) {
                        $this->ensureDir($targetDir);
                        file_put_contents(
                            "{$targetDir}/Dockerfile",
                            $this->twig->render("databases/{$dbType}/Dockerfile.j2", [
                                'image' => $dbImg,
                                'matrix' => $matrix,
                                'build_date' => $buildDate,
                                'channel' => $channel,
                            ])
                        );
                        // Copy support configs if exist
                        $customCnf = $this->templatesDir . "/databases/{$dbType}/custom.cnf";
                        if (file_exists($customCnf)) {
                            copy($customCnf, "{$targetDir}/custom.cnf");
                        }
                        $redisConf = $this->templatesDir . "/databases/{$dbType}/redis.conf";
                        if (file_exists($redisConf)) {
                            copy($redisConf, "{$targetDir}/redis.conf");
                        }
                        $entrypoint = $this->templatesDir . "/databases/{$dbType}/docker-entrypoint-sqlite.sh";
                        if (file_exists($entrypoint)) {
                            copy($entrypoint, "{$targetDir}/docker-entrypoint-sqlite.sh");
                        }
                    }

                    $bakeTargets[$targetName] = [
                        'context' => "./build/databases/{$dbType}/{$ver}",
                        'dockerfile' => 'Dockerfile',
                        'tags' => $tags,
                        'platforms' => ['linux/amd64', 'linux/arm64'],
                    ];
                }
            }
        }

        if (!$catalogOnly) {
            $this->writeBakeFile($bakeTargets);
        }

        $this->catalogManager->generateCatalog($verifiedImages);
    }

    private function expandTags(array $tags, string $registry, string $repoName, string $buildDate, string $channel = 'current'): array
    {
        $expanded = [];
        foreach ($tags as $t) {
            if ($channel === 'dev') {
                $expanded[] = "{$registry}/{$repoName}:{$t}-dev";
                $expanded[] = "{$registry}/{$repoName}:{$t}-dev-{$buildDate}";
            } elseif ($channel === 'stable') {
                $expanded[] = "{$registry}/{$repoName}:{$t}-stable";
                $expanded[] = "{$registry}/{$repoName}:{$t}-stable-{$buildDate}";
            } else {
                // current (default)
                $expanded[] = "{$registry}/{$repoName}:{$t}";
                $expanded[] = "{$registry}/{$repoName}:{$t}-{$buildDate}";
                $expanded[] = "{$registry}/{$repoName}:{$t}-current";
            }
        }
        return array_values(array_unique($expanded));
    }

    private function writeBakeFile(array $targets): void
    {
        $targetNames = array_keys($targets);
        $phpTargets = array_values(array_filter($targetNames, fn($t) => str_starts_with($t, 'php-fpm')));
        $frankenTargets = array_values(array_filter($targetNames, fn($t) => str_starts_with($t, 'frankenphp')));
        $traefikTargets = array_values(array_filter($targetNames, fn($t) => str_starts_with($t, 'traefik')));
        $dbTargets = array_values(array_filter($targetNames, fn($t) => preg_match('/^(mysql|mariadb|postgres|redis|mongodb|sqlite)-/', $t)));
        $webTargets = ['nginx', 'apache', 'openlitespeed', 'caddy', 'lighttpd'];

        $content = "group \"default\" {\n    targets = " . json_encode($targetNames, JSON_UNESCAPED_SLASHES) . "\n}\n\n";
        $content .= "group \"php\" {\n    targets = " . json_encode($phpTargets, JSON_UNESCAPED_SLASHES) . "\n}\n\n";
        $content .= "group \"frankenphp\" {\n    targets = " . json_encode($frankenTargets, JSON_UNESCAPED_SLASHES) . "\n}\n\n";
        $content .= "group \"traefik\" {\n    targets = " . json_encode($traefikTargets, JSON_UNESCAPED_SLASHES) . "\n}\n\n";
        $content .= "group \"webservers\" {\n    targets = " . json_encode($webTargets, JSON_UNESCAPED_SLASHES) . "\n}\n\n";
        $content .= "group \"databases\" {\n    targets = " . json_encode($dbTargets, JSON_UNESCAPED_SLASHES) . "\n}\n\n";

        foreach ($targets as $name => $cfg) {
            $content .= "target \"{$name}\" {\n";
            $content .= "    context = \"{$cfg['context']}\"\n";
            $content .= "    dockerfile = \"{$cfg['dockerfile']}\"\n";
            $content .= "    tags = " . json_encode($cfg['tags'], JSON_UNESCAPED_SLASHES) . "\n";
            $content .= "    platforms = " . json_encode($cfg['platforms'], JSON_UNESCAPED_SLASHES) . "\n";
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
