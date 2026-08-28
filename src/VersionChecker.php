<?php

declare(strict_types=1);

namespace WarpPanel\Images;

use Symfony\Component\Yaml\Yaml;

class VersionChecker
{
    private string $rootDir;
    private string $matrixFile;

    public function __construct(string $rootDir)
    {
        $this->rootDir = rtrim($rootDir, '/');
        $this->matrixFile = $this->rootDir . '/matrix.yaml';
    }

    public function checkAndApplyUpdates(): array
    {
        $matrix = Yaml::parseFile($this->matrixFile);
        $updated = false;
        $updatesLog = [];

        echo "[*] Checking for upstream container updates (Docker Hub & GitHub API)...\n";

        // 1. Check PHP versions (8.0, 8.1, 8.2, 8.3, 8.4)
        foreach ($matrix['images']['php_fpm']['modern'] as &$img) {
            $minor = $img['version'];
            if (!empty($img['is_experimental'])) {
                continue;
            }

            $latestTag = $this->fetchLatestDockerHubTag('library/php', "^{$minor}\.[0-9]+-fpm-alpine");
            if ($latestTag && !empty($img['base_image']) && $img['base_image'] !== "php:{$latestTag}") {
                $oldBase = $img['base_image'];
                $img['base_image'] = "php:{$latestTag}";
                $updated = true;
                $updatesLog[] = "PHP {$minor}: updated from {$oldBase} to php:{$latestTag}";
                echo "  → Updated PHP {$minor}: {$oldBase} -> php:{$latestTag}\n";
            }
        }
        unset($img);

        // 2. Check Nginx
        $latestNginx = $this->fetchLatestDockerHubTag('library/nginx', '^[0-9]+\.[0-9]+-alpine$');
        if ($latestNginx && $matrix['images']['webservers']['nginx']['base_image'] !== "nginx:{$latestNginx}") {
            $old = $matrix['images']['webservers']['nginx']['base_image'];
            $matrix['images']['webservers']['nginx']['base_image'] = "nginx:{$latestNginx}";
            $updated = true;
            $updatesLog[] = "Nginx: updated from {$old} to nginx:{$latestNginx}";
            echo "  → Updated Nginx: {$old} -> nginx:{$latestNginx}\n";
        }

        // 3. Check Apache (HTTPD)
        $latestHttpd = $this->fetchLatestDockerHubTag('library/httpd', '^[0-9]+\.[0-9]+-alpine$');
        if ($latestHttpd && $matrix['images']['webservers']['apache']['base_image'] !== "httpd:{$latestHttpd}") {
            $old = $matrix['images']['webservers']['apache']['base_image'];
            $matrix['images']['webservers']['apache']['base_image'] = "httpd:{$latestHttpd}";
            $updated = true;
            $updatesLog[] = "Apache: updated from {$old} to httpd:{$latestHttpd}";
            echo "  → Updated Apache: {$old} -> httpd:{$latestHttpd}\n";
        }

        // 4. Check FrankenPHP
        foreach ($matrix['images']['frankenphp']['versions'] as &$img) {
            $phpVer = $img['php_version'];
            if (!empty($img['is_experimental'])) {
                continue;
            }
            $latestFranken = $this->fetchLatestDockerHubTag('dunglas/frankenphp', "^[0-9]+-php{$phpVer}-alpine$");
            if ($latestFranken && $img['base_image'] !== "dunglas/frankenphp:{$latestFranken}") {
                $old = $img['base_image'];
                $img['base_image'] = "dunglas/frankenphp:{$latestFranken}";
                $updated = true;
                $updatesLog[] = "FrankenPHP (PHP {$phpVer}): updated from {$old} to dunglas/frankenphp:{$latestFranken}";
                echo "  → Updated FrankenPHP (PHP {$phpVer}): {$old} -> dunglas/frankenphp:{$latestFranken}\n";
            }
        }
        unset($img);

        if ($updated) {
            file_put_contents($this->matrixFile, Yaml::dump($matrix, 6, 2));
            echo "✓ matrix.yaml successfully updated with new upstream versions!\n";
        } else {
            echo "✓ All container base images are already up to date.\n";
        }

        return [
            'updated' => $updated,
            'log' => $updatesLog,
        ];
    }

    private function fetchLatestDockerHubTag(string $repo, string $pattern): ?string
    {
        $url = "https://hub.docker.com/v2/repositories/{$repo}/tags?page_size=50&ordering=last_updated";
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: WarpPanel-VersionChecker/1.0\r\nAccept: application/json\r\n",
                'timeout' => 10,
            ],
        ];

        $json = @file_get_contents($url, false, stream_context_create($opts));
        if ($json === false) {
            return null;
        }

        $data = json_decode($json, true);
        if (!isset($data['results']) || !is_array($data['results'])) {
            return null;
        }

        foreach ($data['results'] as $item) {
            $name = $item['name'] ?? '';
            if (preg_match("/{$pattern}/", $name)) {
                return $name;
            }
        }

        return null;
    }
}
