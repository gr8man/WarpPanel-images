# WarpPanel Images 🚀

Automated build, testing, and distribution system for lightweight, high-performance container images (**Alpine Linux**) tailored for the **WarpPanel** web hosting platform.

---

## 🌟 Key Features

- **Ultra-Lightweight Alpine Base**: Minimal image footprint (tens of MBs instead of hundreds of MBs), fast boot times, and hardened security.
- **Full PHP Version Matrix (5.6 – 8.5)**:
  - **Legacy PHP (5.6, 7.0, 7.1, 7.2, 7.3, 7.4)** with Composer 2.2 LTS.
  - **Modern PHP (8.0, 8.1, 8.2, 8.3, 8.4, 8.5)** with latest Composer 2.x and pre-installed hosting extensions (`pdo_mysql`, `pdo_pgsql`, `pdo_sqlite`, `sqlite3`, `mysqli`, `pgsql`, `redis`, `imagick`, `igbinary`, `imap`, `intl`, `pcntl`, `posix`, `soap`, `sockets`, `xdebug`, `zip`, etc.).
- **Built-in Xdebug Support**:
  - Pre-installed across all PHP versions and ready for step debugging, profiling, and coverage.
  - Disabled by default for zero performance overhead in production (`PHP_XDEBUG_ENABLED=0`).
  - Easily activated on demand with `PHP_XDEBUG_ENABLED=1`.
- **Comprehensive Database Engines**:
  - **MySQL**: 8.0 & 8.4 LTS with utf8mb4 and hosting-optimized buffer pools.
  - **MariaDB**: 10.11 & 11.4 LTS (Alpine) with InnoDB tuning.
  - **PostgreSQL**: 16 & 17 (Alpine) with tuning for concurrent hosting workloads.
  - **Redis**: 7.2 & 7.4 (Alpine) with LRU memory management and AOF/RDB persistence.
  - **MongoDB**: 7.0 & 8.0 document datastore.
  - **SQLite 3**: Embedded and supported out-of-the-box in all PHP runtimes via `pdo_sqlite` and `sqlite3` without extra database containers!
- **Versatile Web Servers**:
  - **Nginx**: FastCGI proxying (TCP/Unix socket) with dynamic DNS resolver, HTTP/2, HTTP/3 (QUIC), and Brotli compression.
  - **Apache HTTPD**: `mpm_event` + `mod_proxy_fcgi` with full `.htaccess` & `mod_rewrite` support.
  - **OpenLiteSpeed**: High-performance HTTP/3 web server with LSCache support.
  - **FrankenPHP**: All-in-one modern PHP runtime (Caddy + PHP) supporting Classic and Worker Mode (Laravel Octane, Symfony Runtime).
- **Serversideup-Compliant Directory & Permission Model**:
  - Base application directory: `/var/www/html`
  - Configurable document root via `WEB_DOCUMENT_ROOT` (defaults to `/var/www/html/public`, automatic fallback to `/var/www/html`).
  - Dynamic user mapping (`PUID` / `PGID`) in `docker-entrypoint.sh` for `www-data` to eliminate host volume permission issues.
- **Traefik & Cloudflare Real-IP Compatibility**:
  - Automatic client IP restoration from `CF-Connecting-IP` and `X-Forwarded-For` across trusted proxy subnets (`TRUSTED_PROXIES`).
- **Built-in Web Application Firewall (WAF) & Security**:
  - Blocks sensitive files (`.env`, `.git`, `.sql`, backups).
  - Protects against Path Traversal, XSS, and SQLi exploit patterns.
  - Injects hardened security headers (`X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`, `Referrer-Policy`).
- **Full Runtime Configurability**:
  - Over 30+ parameters customizable via environment variables (`PHP_MEMORY_LIMIT`, `PHP_XDEBUG_*`, `PHP_OPCACHE_*`, `FPM_PM_*`) or by mounting custom `.ini` / `.conf` files.
- **Automated Verified Catalog Manifests**:
  - Generates machine-readable `catalog.json` and `available-images.json` alongside [CATALOG.md](CATALOG.md) only after integration tests pass.
- **Daily Automated Upstream Version Checks**:
  - Daily GitHub Actions cron checks for newer upstream releases and triggers automatic pull requests.

---

## 📦 Quick Start

### Prerequisites
- **PHP 8.1+** & **Composer**
- **Docker** & **Docker Buildx**

### 1. Install Builder Dependencies
```bash
composer install
```

### 2. Generate Dockerfiles & Bake Manifest
```bash
composer generate
# or: make generate
```

### 3. Build Container Images Locally
```bash
composer build
# or: make build
```

### 4. Run Integration Test Suite
```bash
composer test
# or: make test
```

### 5. Run Security & Antivirus/Malware Scanner (Trivy)
```bash
composer scan
# or: php scripts/scan.php --target=nginx
```

### 6. Check for Upstream Version Updates
```bash
composer check-updates
# or: make check-updates
```

---

## 📋 Available Images & Tags

Detailed manifest and verification status are available in [CATALOG.md](CATALOG.md) and [available-images.json](available-images.json).

### Pulling from GitHub Container Registry (GHCR):
```bash
# PHP-FPM
docker pull ghcr.io/warppanel/php:8.3-fpm
docker pull ghcr.io/warppanel/php:8.4-fpm
docker pull ghcr.io/warppanel/php:7.4-fpm

# Web Servers
docker pull ghcr.io/warppanel/nginx:1.27
docker pull ghcr.io/warppanel/apache:2.4
docker pull ghcr.io/warppanel/openlitespeed:1.8
docker pull ghcr.io/warppanel/caddy:2.8
docker pull ghcr.io/warppanel/lighttpd:1.4

# FrankenPHP
docker pull ghcr.io/warppanel/frankenphp:8.3

# Network Databases
docker pull ghcr.io/warppanel/mysql:8.4
docker pull ghcr.io/warppanel/mariadb:11.4
docker pull ghcr.io/warppanel/postgres:17
docker pull ghcr.io/warppanel/redis:7.4
docker pull ghcr.io/warppanel/mongodb:7.0
```

---

## 🔧 Environment Variables Reference

### 1. Path & Permissions
| Variable | Default | Description |
| :--- | :--- | :--- |
| `APP_DIR` | `/var/www/html` | Application root directory |
| `WEB_DOCUMENT_ROOT` | `/var/www/html/public` | Document root (falls back to `/var/www/html` if `/public` is missing) |
| `PUID` | `1000` | User ID for `www-data` |
| `PGID` | `1000` | Group ID for `www-data` |

### 2. PHP Runtime
| Variable | Default | Description |
| :--- | :--- | :--- |
| `PHP_MEMORY_LIMIT` | `256M` | Script memory limit |
| `PHP_MAX_EXECUTION_TIME` | `60` | Maximum execution time in seconds |
| `PHP_UPLOAD_MAX_FILESIZE` | `64M` | Maximum uploaded file size |
| `PHP_POST_MAX_SIZE` | `64M` | Maximum POST body size |
| `PHP_MAX_INPUT_VARS` | `3000` | Maximum input variables count |
| `PHP_DATE_TIMEZONE` | `UTC` | Default timezone |
| `PHP_DISPLAY_ERRORS` | `Off` | Display errors in browser (`On` / `Off`) |
| `PHP_EXPOSE_PHP` | `Off` | Hide `X-Powered-By: PHP` header |
| `PHP_OPCACHE_ENABLE` | `1` | Enable Zend OPcache |
| `PHP_OPCACHE_MEMORY_CONSUMPTION` | `128` | OPcache memory allocation in MB |

### 3. Xdebug (Debugging & Profiling)
| Variable | Default | Description |
| :--- | :--- | :--- |
| `PHP_XDEBUG_ENABLED` | `0` | Enable Xdebug extension (`1` to enable, `0` for max prod speed) |
| `PHP_XDEBUG_MODE` | `develop,debug` | Xdebug modes (`develop`, `debug`, `coverage`, `profile`, `trace`) |
| `PHP_XDEBUG_CLIENT_HOST` | `host.docker.internal` | Host IP/DNS where IDE (PhpStorm/VS Code) is listening |
| `PHP_XDEBUG_CLIENT_PORT` | `9003` | Xdebug connection port (default 9003 for Xdebug 3) |
| `PHP_XDEBUG_START_WITH_REQUEST` | `trigger` | Trigger mode (`yes`, `no`, `trigger`, `default`) |
| `PHP_XDEBUG_IDEKEY` | `docker` | IDE Key for session mapping |

### 4. Composer
| Variable | Default | Description |
| :--- | :--- | :--- |
| `COMPOSER_ALLOW_SUPERUSER` | `1` | Allow Composer execution as superuser |
| `COMPOSER_HOME` | `/tmp/composer` | Composer cache & config directory |
| `COMPOSER_MEMORY_LIMIT` | `-1` | Unlimited memory for Composer operations |
| `COMPOSER_AUTO_INSTALL` | `0` | Run `composer install` automatically on boot if `composer.json` is found (`1` to enable) |

### 5. Networking, Proxy & Security
| Variable | Default | Description |
| :--- | :--- | :--- |
| `CLOUDFLARE_REAL_IP` | `1` | Restore client IP from `CF-Connecting-IP` |
| `TRUSTED_PROXIES` | `10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 127.0.0.1/32` | Trusted proxy subnets (Traefik/Docker networks) |
| `SECURITY_WAF_ENABLED` | `1` | Enable built-in WAF rules |
| `PHP_FPM_HOST` | `php-fpm` | PHP-FPM container hostname for Nginx/Apache |
| `PHP_FPM_PORT` | `9000` | PHP-FPM TCP port |

---

## 🤖 AI Agents & Developer Guidelines

Guidelines, coding standards, and architectural instructions for AI agents are documented in [AGENTS.md](AGENTS.md).

---

## 📄 License

This project is open-source software licensed under the [MIT License](LICENSE).
