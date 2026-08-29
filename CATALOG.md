# Katalog Sprawdzonych Obrazów WarpPanel

> **Ostatnia aktualizacja:** `2026-08-29T22:14:04+00:00`
> **Rejestr:** `ghcr.io/gr8man`

Automatycznie generowana lista przetestowanych, zweryfikowanych i gotowych do użycia obrazów dla panelu hostingowego WarpPanel.

## 1. PHP-FPM (Alpine Linux)

| Wersja | Typ | Baza Docker | Dostępne Tagi | Główny Tag Rejestru | Status Weryfikacji |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **PHP 8.0** | `modern` | `php:8.0-fpm-alpine` | `8.0-fpm-alpine`, `8.0-fpm` | `ghcr.io/gr8man/php:8.0-fpm-alpine` | ⚡ READY |
| **PHP 8.1** | `modern` | `php:8.1-fpm-alpine` | `8.1-fpm-alpine`, `8.1-fpm` | `ghcr.io/gr8man/php:8.1-fpm-alpine` | ⚡ READY |
| **PHP 8.2** | `modern` | `php:8.2-fpm-alpine` | `8.2-fpm-alpine`, `8.2-fpm` | `ghcr.io/gr8man/php:8.2-fpm-alpine` | ⚡ READY |
| **PHP 8.3** | `modern` | `php:8.3-fpm-alpine` | `8.3-fpm-alpine`, `8.3-fpm`, `latest-fpm` | `ghcr.io/gr8man/php:8.3-fpm-alpine` | ⚡ READY |
| **PHP 8.4** | `modern` | `php:8.4.25-fpm-alpine3.23` | `8.4-fpm-alpine`, `8.4-fpm` | `ghcr.io/gr8man/php:8.4-fpm-alpine` | ⚡ READY |
| **PHP 8.5** | `modern` | `php:8.5-fpm-alpine` | `8.5-fpm-alpine`, `8.5-fpm-dev` | `ghcr.io/gr8man/php:8.5-fpm-alpine` | ⚡ READY |
| **PHP 5.6** | `legacy` | `php:5.6-fpm-alpine` | `5.6-fpm-alpine`, `5.6-fpm` | `ghcr.io/gr8man/php:5.6-fpm-alpine` | ⚡ READY |
| **PHP 7.0** | `legacy` | `php:7.0-fpm-alpine` | `7.0-fpm-alpine`, `7.0-fpm` | `ghcr.io/gr8man/php:7.0-fpm-alpine` | ⚡ READY |
| **PHP 7.1** | `legacy` | `php:7.1-fpm-alpine` | `7.1-fpm-alpine`, `7.1-fpm` | `ghcr.io/gr8man/php:7.1-fpm-alpine` | ⚡ READY |
| **PHP 7.2** | `legacy` | `php:7.2-fpm-alpine` | `7.2-fpm-alpine`, `7.2-fpm` | `ghcr.io/gr8man/php:7.2-fpm-alpine` | ⚡ READY |
| **PHP 7.3** | `legacy` | `php:7.3-fpm-alpine` | `7.3-fpm-alpine`, `7.3-fpm` | `ghcr.io/gr8man/php:7.3-fpm-alpine` | ⚡ READY |
| **PHP 7.4** | `legacy` | `php:7.4-fpm-alpine` | `7.4-fpm-alpine`, `7.4-fpm` | `ghcr.io/gr8man/php:7.4-fpm-alpine` | ⚡ READY |

## 2. FrankenPHP (Caddy + PHP Runtime & Worker Mode)

| Wersja PHP | Baza Docker | Dostępne Tagi | Główny Tag Rejestru | Status Weryfikacji |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **FrankenPHP (PHP 8.2)** | `dunglas/frankenphp:1-php8.2-alpine` | `frankenphp-8.2-alpine`, `frankenphp-8.2` | `ghcr.io/gr8man/frankenphp:frankenphp-8.2-alpine` | ⚡ READY |
| **FrankenPHP (PHP 8.3)** | `dunglas/frankenphp:1-php8.3-alpine` | `frankenphp-8.3-alpine`, `frankenphp-8.3`, `frankenphp-latest` | `ghcr.io/gr8man/frankenphp:frankenphp-8.3-alpine` | ⚡ READY |
| **FrankenPHP (PHP 8.4)** | `dunglas/frankenphp:1-php8.4-alpine` | `frankenphp-8.4-alpine`, `frankenphp-8.4` | `ghcr.io/gr8man/frankenphp:frankenphp-8.4-alpine` | ⚡ READY |
| **FrankenPHP (PHP 8.5)** | `dunglas/frankenphp:latest-alpine` | `frankenphp-8.5-alpine`, `frankenphp-dev` | `ghcr.io/gr8man/frankenphp:frankenphp-8.5-alpine` | ⚡ READY |

## 3. Webserwery Standalone

| Serwer | Baza Docker | Kluczowe Moduły / Cechy | Główny Tag Rejestru | Status Weryfikacji |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **NGINX** | `nginx:1.27-alpine` | `http2`, `http3_quic`, `brotli`, `cloudflare_realip`, `waf_basic` | `ghcr.io/gr8man/nginx:nginx-alpine` | ⚡ READY |
| **APACHE** | `httpd:2.4-alpine` | `mpm_event`, `mod_proxy_fcgi`, `mod_rewrite`, `remoteip`, `waf_basic` | `ghcr.io/gr8man/apache:apache-alpine` | ⚡ READY |
| **OPENLITESPEED** | `litespeedtech/openlitespeed:latest` | `lscache`, `quic`, `waf_rules`, `cloudflare_realip` | `ghcr.io/gr8man/openlitespeed:openlitespeed-alpine` | ⚡ READY |

## 4. Silniki Baz Danych

| Silnik | Wersja | Baza Docker | Dostępne Tagi | Główny Tag Rejestru | Status Weryfikacji |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Mysql** | `8.4` | `mysql:8.4` | `mysql-8.4`, `mysql-lts`, `mysql-latest` | `ghcr.io/gr8man/mysql:mysql-8.4` | ⚡ READY |
| **Mysql** | `8.0` | `mysql:8.0` | `mysql-8.0` | `ghcr.io/gr8man/mysql:mysql-8.0` | ⚡ READY |
| **Mariadb** | `11.4` | `mariadb:11.4` | `mariadb-11.4`, `mariadb-lts`, `mariadb-latest` | `ghcr.io/gr8man/mariadb:mariadb-11.4` | ⚡ READY |
| **Mariadb** | `10.11` | `mariadb:10.11` | `mariadb-10.11`, `mariadb-10.11-lts` | `ghcr.io/gr8man/mariadb:mariadb-10.11` | ⚡ READY |
| **Postgres** | `17` | `postgres:17-alpine` | `postgres-17-alpine`, `postgres-17`, `postgres-latest` | `ghcr.io/gr8man/postgres:postgres-17-alpine` | ⚡ READY |
| **Postgres** | `16` | `postgres:16-alpine` | `postgres-16-alpine`, `postgres-16` | `ghcr.io/gr8man/postgres:postgres-16-alpine` | ⚡ READY |
| **Redis** | `7.4` | `redis:7.4-alpine` | `redis-7.4-alpine`, `redis-7.4`, `redis-latest` | `ghcr.io/gr8man/redis:redis-7.4-alpine` | ⚡ READY |
| **Redis** | `7.2` | `redis:7.2-alpine` | `redis-7.2-alpine`, `redis-7.2` | `ghcr.io/gr8man/redis:redis-7.2-alpine` | ⚡ READY |
| **Mongodb** | `7.0` | `mongo:7.0` | `mongo-7.0`, `mongodb-7.0`, `mongo-latest` | `ghcr.io/gr8man/mongodb:mongo-7.0` | ⚡ READY |
| **Mongodb** | `8.0` | `mongo:8.0` | `mongo-8.0`, `mongodb-8.0` | `ghcr.io/gr8man/mongodb:mongo-8.0` | ⚡ READY |
