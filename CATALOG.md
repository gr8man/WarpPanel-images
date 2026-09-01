# 🚀 WarpPanel Verified Container Images Catalog

> **Release Channel:** `CURRENT`  
> **Last Updated:** `2026-09-01T19:59:40+00:00`  
> **Active Build ID:** `20260901`  
> **Primary Registry:** `ghcr.io/gr8man`  

Central registry and catalog of verified container images for the WarpPanel hosting platform. Each image and release channel (`current`, `stable`, `dev`) has a dedicated software bill of materials (SBOM) in the `catalog/{channel}/` directory detailing installed packages, extensions, and runtime defaults.

## 1. 🐘 PHP-FPM (Alpine Linux)

| Version | Build ID | Type | Base Docker Image | Primary Image Tag | Build Specification | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **PHP 8.0** | `20260901` | `PHP-FPM Modern` | `php:8.0-fpm-alpine` | `ghcr.io/gr8man/php:8.0-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/8.0/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 8.1** | `20260901` | `PHP-FPM Modern` | `php:8.1-fpm-alpine` | `ghcr.io/gr8man/php:8.1-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/8.1/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 8.2** | `20260901` | `PHP-FPM Modern` | `php:8.2-fpm-alpine` | `ghcr.io/gr8man/php:8.2-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/8.2/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 8.3** | `20260901` | `PHP-FPM Modern` | `php:8.3-fpm-alpine` | `ghcr.io/gr8man/php:8.3-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/8.3/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 8.4** | `20260901` | `PHP-FPM Modern` | `php:8.4.25-fpm-alpine3.23` | `ghcr.io/gr8man/php:8.4-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/8.4/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 8.5** | `20260901` | `PHP-FPM Modern` | `php:8.5-fpm-alpine` | `ghcr.io/gr8man/php:8.5-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/8.5/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 5.6** | `20260901` | `PHP-FPM Legacy` | `php:5.6-fpm-alpine` | `ghcr.io/gr8man/php:5.6-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/5.6/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 7.0** | `20260901` | `PHP-FPM Legacy` | `php:7.0-fpm-alpine` | `ghcr.io/gr8man/php:7.0-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/7.0/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 7.1** | `20260901` | `PHP-FPM Legacy` | `php:7.1-fpm-alpine` | `ghcr.io/gr8man/php:7.1-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/7.1/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 7.2** | `20260901` | `PHP-FPM Legacy` | `php:7.2-fpm-alpine` | `ghcr.io/gr8man/php:7.2-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/7.2/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 7.3** | `20260901` | `PHP-FPM Legacy` | `php:7.3-fpm-alpine` | `ghcr.io/gr8man/php:7.3-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/7.3/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 7.4** | `20260901` | `PHP-FPM Legacy` | `php:7.4-fpm-alpine` | `ghcr.io/gr8man/php:7.4-fpm-alpine-20260901` | [📄 Specification 20260901](catalog/current/php-fpm/7.4/20260901.json) | ✅ **VERIFIED (PASS)** |

## 2. ⚡ FrankenPHP (All-in-One Caddy + PHP + Worker Mode)

| PHP Version | Build ID | Engine / Server | Base Docker Image | Primary Image Tag | Build Specification | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **PHP 8.2** | `20260901` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:1-php8.2-alpine` | `ghcr.io/gr8man/frankenphp:8.2-alpine-20260901` | [📄 Specification 20260901](catalog/current/frankenphp/8.2/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 8.3** | `20260901` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:1-php8.3-alpine` | `ghcr.io/gr8man/frankenphp:8.3-alpine-20260901` | [📄 Specification 20260901](catalog/current/frankenphp/8.3/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 8.4** | `20260901` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:1-php8.4-alpine` | `ghcr.io/gr8man/frankenphp:8.4-alpine-20260901` | [📄 Specification 20260901](catalog/current/frankenphp/8.4/20260901.json) | ✅ **VERIFIED (PASS)** |
| **PHP 8.5** | `20260901` | FrankenPHP 1.x (Caddy v2) | `dunglas/frankenphp:latest-alpine` | `ghcr.io/gr8man/frankenphp:8.5-alpine-20260901` | [📄 Specification 20260901](catalog/current/frankenphp/8.5/20260901.json) | ✅ **VERIFIED (PASS)** |

## 3. 🌐 Web Servers (Standalone)

| Server | Build ID | Base Docker Image | Features / Protocols | Primary Image Tag | Build Specification | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **NGINX** | `20260901` | `nginx:1.27-alpine` | `http2`, `http3_quic`, `brotli`, `cloudflare_realip`, `waf_basic` | `ghcr.io/gr8man/nginx:1.27-alpine-20260901` | [📄 Specification 20260901](catalog/current/webservers/nginx/20260901.json) | ✅ **VERIFIED (PASS)** |
| **APACHE** | `20260901` | `httpd:2.4-alpine` | `mpm_event`, `mod_proxy_fcgi`, `mod_rewrite`, `remoteip`, `waf_basic` | `ghcr.io/gr8man/apache:2.4-alpine-20260901` | [📄 Specification 20260901](catalog/current/webservers/apache/20260901.json) | ✅ **VERIFIED (PASS)** |
| **OPENLITESPEED** | `20260901` | `litespeedtech/openlitespeed:latest` | `lscache`, `quic`, `waf_rules`, `cloudflare_realip` | `ghcr.io/gr8man/openlitespeed:1.8-alpine-20260901` | [📄 Specification 20260901](catalog/current/webservers/openlitespeed/20260901.json) | ✅ **VERIFIED (PASS)** |
| **CADDY** | `20260901` | `caddy:2.8-alpine` | `auto_https`, `http3_quic`, `zstd_gzip`, `cloudflare_realip`, `waf_basic`, `fastcgi_php` | `ghcr.io/gr8man/caddy:2.8-alpine-20260901` | [📄 Specification 20260901](catalog/current/webservers/caddy/20260901.json) | ✅ **VERIFIED (PASS)** |
| **LIGHTTPD** | `20260901` | `alpine:3.20` | `fastcgi_php`, `mod_rewrite`, `mod_deflate`, `cloudflare_realip`, `waf_basic` | `ghcr.io/gr8man/lighttpd:1.4-alpine-20260901` | [📄 Specification 20260901](catalog/current/webservers/lighttpd/20260901.json) | ✅ **VERIFIED (PASS)** |

## 4. 🚦 Traefik (Cloud-Native Ingress, Reverse Proxy & Load Balancer)

| Version | Build ID | Base Docker Image | Features / Protocols | Primary Image Tag | Build Specification | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Traefik v2.11** | `20260901` | `traefik:v2.11` | `docker_provider`, `acme_letsencrypt`, `cloudflare_realip`, `http_to_https`, `dashboard` | `ghcr.io/gr8man/traefik:2.11-20260901` | [📄 Specification 20260901](catalog/current/traefik/2.11/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Traefik v3.1** | `20260901` | `traefik:v3.1` | `docker_provider`, `http3_quic`, `acme_letsencrypt`, `cloudflare_realip`, `http_to_https`, `dashboard` | `ghcr.io/gr8man/traefik:3.1-20260901` | [📄 Specification 20260901](catalog/current/traefik/3.1/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Traefik v3.2** | `20260901` | `traefik:v3.2` | `docker_provider`, `http3_quic`, `acme_letsencrypt`, `cloudflare_realip`, `http_to_https`, `dashboard` | `ghcr.io/gr8man/traefik:3.2-20260901` | [📄 Specification 20260901](catalog/current/traefik/3.2/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Traefik v3.3** | `20260901` | `traefik:v3.3` | `docker_provider`, `http3_quic`, `acme_letsencrypt`, `cloudflare_realip`, `http_to_https`, `dashboard` | `ghcr.io/gr8man/traefik:3.3-20260901` | [📄 Specification 20260901](catalog/current/traefik/3.3/20260901.json) | ✅ **VERIFIED (PASS)** |

## 5. 🗄️ Network Databases & Caching Engines

| Database / Engine | Version | Build ID | Base Docker Image | Primary Image Tag | Build Specification | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Mysql** | `8.4` | `20260901` | `mysql:8.4` | `ghcr.io/gr8man/mysql:8.4-20260901` | [📄 Specification 20260901](catalog/current/databases/mysql-8.4/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Mysql** | `8.0` | `20260901` | `mysql:8.0` | `ghcr.io/gr8man/mysql:8.0-20260901` | [📄 Specification 20260901](catalog/current/databases/mysql-8.0/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Mariadb** | `11.4` | `20260901` | `mariadb:11.4` | `ghcr.io/gr8man/mariadb:11.4-20260901` | [📄 Specification 20260901](catalog/current/databases/mariadb-11.4/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Mariadb** | `10.11` | `20260901` | `mariadb:10.11` | `ghcr.io/gr8man/mariadb:10.11-20260901` | [📄 Specification 20260901](catalog/current/databases/mariadb-10.11/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Postgres** | `17` | `20260901` | `postgres:17-alpine` | `ghcr.io/gr8man/postgres:17-alpine-20260901` | [📄 Specification 20260901](catalog/current/databases/postgres-17/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Postgres** | `16` | `20260901` | `postgres:16-alpine` | `ghcr.io/gr8man/postgres:16-alpine-20260901` | [📄 Specification 20260901](catalog/current/databases/postgres-16/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Redis** | `7.4` | `20260901` | `redis:7.4-alpine` | `ghcr.io/gr8man/redis:7.4-alpine-20260901` | [📄 Specification 20260901](catalog/current/databases/redis-7.4/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Redis** | `7.2` | `20260901` | `redis:7.2-alpine` | `ghcr.io/gr8man/redis:7.2-alpine-20260901` | [📄 Specification 20260901](catalog/current/databases/redis-7.2/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Mongodb** | `7.0` | `20260901` | `mongo:7.0` | `ghcr.io/gr8man/mongodb:7.0-20260901` | [📄 Specification 20260901](catalog/current/databases/mongodb-7.0/20260901.json) | ✅ **VERIFIED (PASS)** |
| **Mongodb** | `8.0` | `20260901` | `mongo:8.0` | `ghcr.io/gr8man/mongodb:8.0-20260901` | [📄 Specification 20260901](catalog/current/databases/mongodb-8.0/20260901.json) | ✅ **VERIFIED (PASS)** |

---
*Detailed software bill of materials and build specifications are located in the `catalog/` directory.*
