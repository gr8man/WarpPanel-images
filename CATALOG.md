# 🚀 Katalog Sprawdzonych Obrazów WarpPanel

> **Kanał Wydań:** `CURRENT`  
> **Ostatnia aktualizacja:** `2026-08-29T23:24:22+00:00`  
> **Aktywny Build ID:** `20260829`  
> **Rejestr Główny:** `ghcr.io/warppanel`  

Centralny rejestr i katalog zweryfikowanych obrazów kontenerowych dla platformy hostingowej WarpPanel. Każdy obraz i kanał (`current`, `stable`, `dev`) posiada dedykowaną specyfikację oprogramowania w katalogu `catalog/{channel}/` z listą zainstalowanych pakietów, modułów i konfiguracji runtime.

## 1. 🐘 PHP-FPM (Alpine Linux)

| Wersja | Build ID | Typ | Baza Docker | Główny Tag Obrazu | Specyfikacja Buildu | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **PHP 8.0** | `20260829` | `PHP-FPM Modern` | `php:8.0-fpm-alpine` | `ghcr.io/warppanel/php:8.0-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/8.0/20260829.json) | ⚡ READY |
| **PHP 8.1** | `20260829` | `PHP-FPM Modern` | `php:8.1-fpm-alpine` | `ghcr.io/warppanel/php:8.1-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/8.1/20260829.json) | ⚡ READY |
| **PHP 8.2** | `20260829` | `PHP-FPM Modern` | `php:8.2-fpm-alpine` | `ghcr.io/warppanel/php:8.2-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/8.2/20260829.json) | ⚡ READY |
| **PHP 8.3** | `20260829` | `PHP-FPM Modern` | `php:8.3-fpm-alpine` | `ghcr.io/warppanel/php:8.3-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/8.3/20260829.json) | ⚡ READY |
| **PHP 8.4** | `20260829` | `PHP-FPM Modern` | `php:8.4.25-fpm-alpine3.23` | `ghcr.io/warppanel/php:8.4-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/8.4/20260829.json) | ⚡ READY |
| **PHP 8.5** | `20260829` | `PHP-FPM Modern` | `php:8.5-fpm-alpine` | `ghcr.io/warppanel/php:8.5-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/8.5/20260829.json) | ⚡ READY |
| **PHP 5.6** | `20260829` | `PHP-FPM Legacy` | `php:5.6-fpm-alpine` | `ghcr.io/warppanel/php:5.6-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/5.6/20260829.json) | ⚡ READY |
| **PHP 7.0** | `20260829` | `PHP-FPM Legacy` | `php:7.0-fpm-alpine` | `ghcr.io/warppanel/php:7.0-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/7.0/20260829.json) | ⚡ READY |
| **PHP 7.1** | `20260829` | `PHP-FPM Legacy` | `php:7.1-fpm-alpine` | `ghcr.io/warppanel/php:7.1-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/7.1/20260829.json) | ⚡ READY |
| **PHP 7.2** | `20260829` | `PHP-FPM Legacy` | `php:7.2-fpm-alpine` | `ghcr.io/warppanel/php:7.2-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/7.2/20260829.json) | ⚡ READY |
| **PHP 7.3** | `20260829` | `PHP-FPM Legacy` | `php:7.3-fpm-alpine` | `ghcr.io/warppanel/php:7.3-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/7.3/20260829.json) | ⚡ READY |
| **PHP 7.4** | `20260829` | `PHP-FPM Legacy` | `php:7.4-fpm-alpine` | `ghcr.io/warppanel/php:7.4-fpm-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/php-fpm/7.4/20260829.json) | ⚡ READY |

## 2. ⚡ FrankenPHP (All-in-One Caddy + PHP + Worker Mode)

| Wersja PHP | Build ID | Silnik / Serwer | Baza Docker | Główny Tag Obrazu | Specyfikacja Buildu | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **PHP 8.2** | `20260829` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:1-php8.2-alpine` | `ghcr.io/warppanel/frankenphp:frankenphp-8.2-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/frankenphp/8.2/20260829.json) | ⚡ READY |
| **PHP 8.3** | `20260829` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:1-php8.3-alpine` | `ghcr.io/warppanel/frankenphp:frankenphp-8.3-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/frankenphp/8.3/20260829.json) | ⚡ READY |
| **PHP 8.4** | `20260829` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:1-php8.4-alpine` | `ghcr.io/warppanel/frankenphp:frankenphp-8.4-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/frankenphp/8.4/20260829.json) | ⚡ READY |
| **PHP 8.5** | `20260829` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:latest-alpine` | `ghcr.io/warppanel/frankenphp:frankenphp-8.5-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/frankenphp/8.5/20260829.json) | ⚡ READY |

## 3. 🌐 Serwery WWW (Standalone)

| Serwer | Build ID | Baza Docker | Cechy / Protokoły | Główny Tag Obrazu | Specyfikacja Buildu | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **NGINX** | `20260829` | `nginx:1.27-alpine` | `http2`, `http3_quic`, `brotli`, `cloudflare_realip`, `waf_basic` | `ghcr.io/warppanel/nginx:nginx-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/webservers/nginx/20260829.json) | ⚡ READY |
| **APACHE** | `20260829` | `httpd:2.4-alpine` | `mpm_event`, `mod_proxy_fcgi`, `mod_rewrite`, `remoteip`, `waf_basic` | `ghcr.io/warppanel/apache:apache-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/webservers/apache/20260829.json) | ⚡ READY |
| **OPENLITESPEED** | `20260829` | `litespeedtech/openlitespeed:latest` | `lscache`, `quic`, `waf_rules`, `cloudflare_realip` | `ghcr.io/warppanel/openlitespeed:openlitespeed-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/webservers/openlitespeed/20260829.json) | ⚡ READY |

## 4. 🗄️ Sieciowe Bazy Danych i Pamięć Podręczna

| Baza / Silnik | Wersja | Build ID | Baza Docker | Główny Tag Obrazu | Specyfikacja Buildu | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Mysql** | `8.4` | `20260829` | `mysql:8.4` | `ghcr.io/warppanel/mysql:mysql-8.4-20260829` | [📄 Specyfikacja 20260829](catalog/current/databases/mysql-8.4/20260829.json) | ⚡ READY |
| **Mysql** | `8.0` | `20260829` | `mysql:8.0` | `ghcr.io/warppanel/mysql:mysql-8.0-20260829` | [📄 Specyfikacja 20260829](catalog/current/databases/mysql-8.0/20260829.json) | ⚡ READY |
| **Mariadb** | `11.4` | `20260829` | `mariadb:11.4` | `ghcr.io/warppanel/mariadb:mariadb-11.4-20260829` | [📄 Specyfikacja 20260829](catalog/current/databases/mariadb-11.4/20260829.json) | ⚡ READY |
| **Mariadb** | `10.11` | `20260829` | `mariadb:10.11` | `ghcr.io/warppanel/mariadb:mariadb-10.11-20260829` | [📄 Specyfikacja 20260829](catalog/current/databases/mariadb-10.11/20260829.json) | ⚡ READY |
| **Postgres** | `17` | `20260829` | `postgres:17-alpine` | `ghcr.io/warppanel/postgres:postgres-17-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/databases/postgres-17/20260829.json) | ⚡ READY |
| **Postgres** | `16` | `20260829` | `postgres:16-alpine` | `ghcr.io/warppanel/postgres:postgres-16-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/databases/postgres-16/20260829.json) | ⚡ READY |
| **Redis** | `7.4` | `20260829` | `redis:7.4-alpine` | `ghcr.io/warppanel/redis:redis-7.4-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/databases/redis-7.4/20260829.json) | ⚡ READY |
| **Redis** | `7.2` | `20260829` | `redis:7.2-alpine` | `ghcr.io/warppanel/redis:redis-7.2-alpine-20260829` | [📄 Specyfikacja 20260829](catalog/current/databases/redis-7.2/20260829.json) | ⚡ READY |
| **Mongodb** | `7.0` | `20260829` | `mongo:7.0` | `ghcr.io/warppanel/mongodb:mongo-7.0-20260829` | [📄 Specyfikacja 20260829](catalog/current/databases/mongodb-7.0/20260829.json) | ⚡ READY |
| **Mongodb** | `8.0` | `20260829` | `mongo:8.0` | `ghcr.io/warppanel/mongodb:mongo-8.0-20260829` | [📄 Specyfikacja 20260829](catalog/current/databases/mongodb-8.0/20260829.json) | ⚡ READY |

---
*Wszystkie szczegółowe specyfikacje buildów znajdują się w folderze `catalog/`.*
