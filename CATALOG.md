# 🚀 Katalog Sprawdzonych Obrazów WarpPanel

> **Kanał Wydań:** `CURRENT`  
> **Ostatnia aktualizacja:** `2026-08-30T03:51:03+00:00`  
> **Aktywny Build ID:** `20260830`  
> **Rejestr Główny:** `ghcr.io/gr8man`  

Centralny rejestr i katalog zweryfikowanych obrazów kontenerowych dla platformy hostingowej WarpPanel. Każdy obraz i kanał (`current`, `stable`, `dev`) posiada dedykowaną specyfikację oprogramowania w katalogu `catalog/{channel}/` z listą zainstalowanych pakietów, modułów i konfiguracji runtime.

## 1. 🐘 PHP-FPM (Alpine Linux)

| Wersja | Build ID | Typ | Baza Docker | Główny Tag Obrazu | Specyfikacja Buildu | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **PHP 8.0** | `20260830` | `PHP-FPM Modern` | `php:8.0-fpm-alpine` | `ghcr.io/gr8man/php:8.0-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/8.0/20260830.json) | ⚡ READY |
| **PHP 8.1** | `20260830` | `PHP-FPM Modern` | `php:8.1-fpm-alpine` | `ghcr.io/gr8man/php:8.1-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/8.1/20260830.json) | ⚡ READY |
| **PHP 8.2** | `20260830` | `PHP-FPM Modern` | `php:8.2-fpm-alpine` | `ghcr.io/gr8man/php:8.2-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/8.2/20260830.json) | ⚡ READY |
| **PHP 8.3** | `20260830` | `PHP-FPM Modern` | `php:8.3-fpm-alpine` | `ghcr.io/gr8man/php:8.3-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/8.3/20260830.json) | ⚡ READY |
| **PHP 8.4** | `20260830` | `PHP-FPM Modern` | `php:8.4.25-fpm-alpine3.23` | `ghcr.io/gr8man/php:8.4-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/8.4/20260830.json) | ⚡ READY |
| **PHP 8.5** | `20260830` | `PHP-FPM Modern` | `php:8.5-fpm-alpine` | `ghcr.io/gr8man/php:8.5-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/8.5/20260830.json) | ⚡ READY |
| **PHP 5.6** | `20260830` | `PHP-FPM Legacy` | `php:5.6-fpm-alpine` | `ghcr.io/gr8man/php:5.6-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/5.6/20260830.json) | ⚡ READY |
| **PHP 7.0** | `20260830` | `PHP-FPM Legacy` | `php:7.0-fpm-alpine` | `ghcr.io/gr8man/php:7.0-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/7.0/20260830.json) | ⚡ READY |
| **PHP 7.1** | `20260830` | `PHP-FPM Legacy` | `php:7.1-fpm-alpine` | `ghcr.io/gr8man/php:7.1-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/7.1/20260830.json) | ⚡ READY |
| **PHP 7.2** | `20260830` | `PHP-FPM Legacy` | `php:7.2-fpm-alpine` | `ghcr.io/gr8man/php:7.2-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/7.2/20260830.json) | ⚡ READY |
| **PHP 7.3** | `20260830` | `PHP-FPM Legacy` | `php:7.3-fpm-alpine` | `ghcr.io/gr8man/php:7.3-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/7.3/20260830.json) | ⚡ READY |
| **PHP 7.4** | `20260830` | `PHP-FPM Legacy` | `php:7.4-fpm-alpine` | `ghcr.io/gr8man/php:7.4-fpm-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/php-fpm/7.4/20260830.json) | ⚡ READY |

## 2. ⚡ FrankenPHP (All-in-One Caddy + PHP + Worker Mode)

| Wersja PHP | Build ID | Silnik / Serwer | Baza Docker | Główny Tag Obrazu | Specyfikacja Buildu | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **PHP 8.2** | `20260830` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:1-php8.2-alpine` | `ghcr.io/gr8man/frankenphp:frankenphp-8.2-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/frankenphp/8.2/20260830.json) | ⚡ READY |
| **PHP 8.3** | `20260830` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:1-php8.3-alpine` | `ghcr.io/gr8man/frankenphp:frankenphp-8.3-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/frankenphp/8.3/20260830.json) | ⚡ READY |
| **PHP 8.4** | `20260830` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:1-php8.4-alpine` | `ghcr.io/gr8man/frankenphp:frankenphp-8.4-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/frankenphp/8.4/20260830.json) | ⚡ READY |
| **PHP 8.5** | `20260830` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:latest-alpine` | `ghcr.io/gr8man/frankenphp:frankenphp-8.5-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/frankenphp/8.5/20260830.json) | ⚡ READY |

## 3. 🌐 Serwery WWW (Standalone)

| Serwer | Build ID | Baza Docker | Cechy / Protokoły | Główny Tag Obrazu | Specyfikacja Buildu | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **NGINX** | `20260830` | `nginx:1.27-alpine` | `http2`, `http3_quic`, `brotli`, `cloudflare_realip`, `waf_basic` | `ghcr.io/gr8man/nginx:nginx-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/webservers/nginx/20260830.json) | ⚡ READY |
| **APACHE** | `20260830` | `httpd:2.4-alpine` | `mpm_event`, `mod_proxy_fcgi`, `mod_rewrite`, `remoteip`, `waf_basic` | `ghcr.io/gr8man/apache:apache-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/webservers/apache/20260830.json) | ⚡ READY |
| **OPENLITESPEED** | `20260830` | `litespeedtech/openlitespeed:latest` | `lscache`, `quic`, `waf_rules`, `cloudflare_realip` | `ghcr.io/gr8man/openlitespeed:openlitespeed-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/webservers/openlitespeed/20260830.json) | ⚡ READY |
| **CADDY** | `20260830` | `caddy:2.8-alpine` | `auto_https`, `http3_quic`, `zstd_gzip`, `cloudflare_realip`, `waf_basic`, `fastcgi_php` | `ghcr.io/gr8man/caddy:caddy-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/webservers/caddy/20260830.json) | ⚡ READY |
| **LIGHTTPD** | `20260830` | `alpine:3.20` | `fastcgi_php`, `mod_rewrite`, `mod_deflate`, `cloudflare_realip`, `waf_basic` | `ghcr.io/gr8man/lighttpd:lighttpd-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/webservers/lighttpd/20260830.json) | ⚡ READY |

## 4. 🚦 Traefik (Cloud-Native Ingress, Reverse Proxy & Load Balancer)

| Wersja | Build ID | Baza Docker | Cechy / Protokoły | Główny Tag Obrazu | Specyfikacja Buildu | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Traefik v2.11** | `20260830` | `traefik:v2.11` | `docker_provider`, `acme_letsencrypt`, `cloudflare_realip`, `http_to_https`, `dashboard` | `ghcr.io/gr8man/traefik:traefik-2.11-20260830` | [📄 Specyfikacja 20260830](catalog/current/traefik/2.11/20260830.json) | ⚡ READY |
| **Traefik v3.1** | `20260830` | `traefik:v3.1` | `docker_provider`, `http3_quic`, `acme_letsencrypt`, `cloudflare_realip`, `http_to_https`, `dashboard` | `ghcr.io/gr8man/traefik:traefik-3.1-20260830` | [📄 Specyfikacja 20260830](catalog/current/traefik/3.1/20260830.json) | ⚡ READY |
| **Traefik v3.2** | `20260830` | `traefik:v3.2` | `docker_provider`, `http3_quic`, `acme_letsencrypt`, `cloudflare_realip`, `http_to_https`, `dashboard` | `ghcr.io/gr8man/traefik:traefik-3.2-20260830` | [📄 Specyfikacja 20260830](catalog/current/traefik/3.2/20260830.json) | ⚡ READY |
| **Traefik v3.3** | `20260830` | `traefik:v3.3` | `docker_provider`, `http3_quic`, `acme_letsencrypt`, `cloudflare_realip`, `http_to_https`, `dashboard` | `ghcr.io/gr8man/traefik:traefik-3.3-20260830` | [📄 Specyfikacja 20260830](catalog/current/traefik/3.3/20260830.json) | ⚡ READY |

## 5. 🗄️ Sieciowe Bazy Danych i Pamięć Podręczna

| Baza / Silnik | Wersja | Build ID | Baza Docker | Główny Tag Obrazu | Specyfikacja Buildu | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Mysql** | `8.4` | `20260830` | `mysql:8.4` | `ghcr.io/gr8man/mysql:mysql-8.4-20260830` | [📄 Specyfikacja 20260830](catalog/current/databases/mysql-8.4/20260830.json) | ⚡ READY |
| **Mysql** | `8.0` | `20260830` | `mysql:8.0` | `ghcr.io/gr8man/mysql:mysql-8.0-20260830` | [📄 Specyfikacja 20260830](catalog/current/databases/mysql-8.0/20260830.json) | ⚡ READY |
| **Mariadb** | `11.4` | `20260830` | `mariadb:11.4` | `ghcr.io/gr8man/mariadb:mariadb-11.4-20260830` | [📄 Specyfikacja 20260830](catalog/current/databases/mariadb-11.4/20260830.json) | ⚡ READY |
| **Mariadb** | `10.11` | `20260830` | `mariadb:10.11` | `ghcr.io/gr8man/mariadb:mariadb-10.11-20260830` | [📄 Specyfikacja 20260830](catalog/current/databases/mariadb-10.11/20260830.json) | ⚡ READY |
| **Postgres** | `17` | `20260830` | `postgres:17-alpine` | `ghcr.io/gr8man/postgres:postgres-17-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/databases/postgres-17/20260830.json) | ⚡ READY |
| **Postgres** | `16` | `20260830` | `postgres:16-alpine` | `ghcr.io/gr8man/postgres:postgres-16-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/databases/postgres-16/20260830.json) | ⚡ READY |
| **Redis** | `7.4` | `20260830` | `redis:7.4-alpine` | `ghcr.io/gr8man/redis:redis-7.4-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/databases/redis-7.4/20260830.json) | ⚡ READY |
| **Redis** | `7.2` | `20260830` | `redis:7.2-alpine` | `ghcr.io/gr8man/redis:redis-7.2-alpine-20260830` | [📄 Specyfikacja 20260830](catalog/current/databases/redis-7.2/20260830.json) | ⚡ READY |
| **Mongodb** | `7.0` | `20260830` | `mongo:7.0` | `ghcr.io/gr8man/mongodb:mongo-7.0-20260830` | [📄 Specyfikacja 20260830](catalog/current/databases/mongodb-7.0/20260830.json) | ⚡ READY |
| **Mongodb** | `8.0` | `20260830` | `mongo:8.0` | `ghcr.io/gr8man/mongodb:mongo-8.0-20260830` | [📄 Specyfikacja 20260830](catalog/current/databases/mongodb-8.0/20260830.json) | ⚡ READY |

---
*Wszystkie szczegółowe specyfikacje buildów znajdują się w folderze `catalog/`.*
