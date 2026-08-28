# Katalog Sprawdzonych Obrazów WarpPanel

> **Ostatnia aktualizacja:** `2026-08-28T22:55:04+00:00`
> **Rejestr:** `ghcr.io/warppanel`

Automatycznie generowana lista przetestowanych, zweryfikowanych i gotowych do użycia obrazów dla panelu hostingowego WarpPanel.

## 1. PHP-FPM (Alpine Linux)

| Wersja | Typ | Baza Docker | Dostępne Tagi | Główny Tag Rejestru | Status Weryfikacji |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **PHP 8.0** | `modern` | `php:8.0.30-fpm-alpine3.16` | `8.0-fpm-alpine`, `8.0-fpm` | `ghcr.io/warppanel/php:8.0-fpm-alpine` | ⚡ READY |
| **PHP 8.1** | `modern` | `php:8.1.31-fpm-alpine3.20` | `8.1-fpm-alpine`, `8.1-fpm` | `ghcr.io/warppanel/php:8.1-fpm-alpine` | ⚡ READY |
| **PHP 8.2** | `modern` | `php:8.2.27-fpm-alpine3.20` | `8.2-fpm-alpine`, `8.2-fpm` | `ghcr.io/warppanel/php:8.2-fpm-alpine` | ⚡ READY |
| **PHP 8.3** | `modern` | `php:8.3.17-fpm-alpine3.21` | `8.3-fpm-alpine`, `8.3-fpm`, `latest-fpm` | `ghcr.io/warppanel/php:8.3-fpm-alpine` | ⚡ READY |
| **PHP 8.4** | `modern` | `php:8.4.4-fpm-alpine3.21` | `8.4-fpm-alpine`, `8.4-fpm` | `ghcr.io/warppanel/php:8.4-fpm-alpine` | ⚡ READY |
| **PHP 8.5** | `modern` | `php:8.5.0-dev-fpm-alpine` | `8.5-fpm-alpine`, `8.5-fpm-dev` | `ghcr.io/warppanel/php:8.5-fpm-alpine` | ⚡ READY |
| **PHP 5.6** | `legacy` | `alpine:3.8` | `5.6-fpm-alpine`, `5.6-fpm` | `ghcr.io/warppanel/php:5.6-fpm-alpine` | ⚡ READY |
| **PHP 7.0** | `legacy` | `alpine:3.7` | `7.0-fpm-alpine`, `7.0-fpm` | `ghcr.io/warppanel/php:7.0-fpm-alpine` | ⚡ READY |
| **PHP 7.1** | `legacy` | `alpine:3.8` | `7.1-fpm-alpine`, `7.1-fpm` | `ghcr.io/warppanel/php:7.1-fpm-alpine` | ⚡ READY |
| **PHP 7.2** | `legacy` | `alpine:3.9` | `7.2-fpm-alpine`, `7.2-fpm` | `ghcr.io/warppanel/php:7.2-fpm-alpine` | ⚡ READY |
| **PHP 7.3** | `legacy` | `alpine:3.10` | `7.3-fpm-alpine`, `7.3-fpm` | `ghcr.io/warppanel/php:7.3-fpm-alpine` | ⚡ READY |
| **PHP 7.4** | `legacy` | `php:7.4.33-fpm-alpine3.15` | `7.4-fpm-alpine`, `7.4-fpm` | `ghcr.io/warppanel/php:7.4-fpm-alpine` | ⚡ READY |

## 2. FrankenPHP (Caddy + PHP Runtime & Worker Mode)

| Wersja PHP | Baza Docker | Dostępne Tagi | Główny Tag Rejestru | Status Weryfikacji |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **FrankenPHP (PHP 8.1)** | `dunglas/frankenphp:1-php8.1-alpine` | `frankenphp-8.1-alpine`, `frankenphp-8.1` | `ghcr.io/warppanel/frankenphp:frankenphp-8.1-alpine` | ⚡ READY |
| **FrankenPHP (PHP 8.2)** | `dunglas/frankenphp:1-php8.2-alpine` | `frankenphp-8.2-alpine`, `frankenphp-8.2` | `ghcr.io/warppanel/frankenphp:frankenphp-8.2-alpine` | ⚡ READY |
| **FrankenPHP (PHP 8.3)** | `dunglas/frankenphp:1-php8.3-alpine` | `frankenphp-8.3-alpine`, `frankenphp-8.3`, `frankenphp-latest` | `ghcr.io/warppanel/frankenphp:frankenphp-8.3-alpine` | ⚡ READY |
| **FrankenPHP (PHP 8.4)** | `dunglas/frankenphp:1-php8.4-alpine` | `frankenphp-8.4-alpine`, `frankenphp-8.4` | `ghcr.io/warppanel/frankenphp:frankenphp-8.4-alpine` | ⚡ READY |
| **FrankenPHP (PHP 8.5)** | `dunglas/frankenphp:latest-alpine` | `frankenphp-8.5-alpine`, `frankenphp-dev` | `ghcr.io/warppanel/frankenphp:frankenphp-8.5-alpine` | ⚡ READY |

## 3. Webserwery Standalone

| Serwer | Baza Docker | Kluczowe Moduły / Cechy | Główny Tag Rejestru | Status Weryfikacji |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **NGINX** | `nginx:1.27-alpine` | `http2`, `http3_quic`, `brotli`, `cloudflare_realip`, `waf_basic` | `ghcr.io/warppanel/nginx:nginx-alpine` | ⚡ READY |
| **APACHE** | `httpd:2.4-alpine` | `mpm_event`, `mod_proxy_fcgi`, `mod_rewrite`, `remoteip`, `waf_basic` | `ghcr.io/warppanel/apache:apache-alpine` | ⚡ READY |
| **OPENLITESPEED** | `litespeedtech/openlitespeed:1.8-alpine` | `lscache`, `quic`, `waf_rules`, `cloudflare_realip` | `ghcr.io/warppanel/openlitespeed:openlitespeed-alpine` | ⚡ READY |
