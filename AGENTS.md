# WarpPanel Images — Instructions for AI Agents (AGENTS.md)

The **WarpPanel-images** repository is responsible for the automated generation, building, end-to-end integration testing, and publishing of lightweight, optimized container images (**Alpine Linux** & official Docker Hub base images) for the **WarpPanel** hosting system.

## Builder Engine (PHP & Composer)
The project utilizes **PHP 8.1+ & Composer** (`symfony/yaml`, `twig/twig`) to parse matrices and render templates.

### Available Composer Commands
- `composer generate` — Generates Dockerfiles, manifests, and `docker-bake.hcl` from `matrix.yaml` and Twig templates.
- `composer build` — Builds container images locally using `docker buildx bake`.
- `composer test` — Executes automated cross-stack integration tests (Web + PHP-FPM / FrankenPHP / Databases).
- `composer scan` — Scans container images for CVE vulnerabilities, malware, and security misconfigurations via Trivy.
- `composer catalog` — Updates the verified images catalog (`catalog.json`, `available-images.json`, `CATALOG.md`).

## Container Architecture Standards
1. **Alpine Linux Base & Official Docker Hub Images**: All images are built on lightweight, secure Alpine Linux or official Docker Hub bases for fast boot times and minimal resource footprint.
2. **Path & Permissions Standard (serversideup-inspired)**:
   - Default application directory: `/var/www/html`
   - DocumentRoot: Environment variable `WEB_DOCUMENT_ROOT` (defaults to `/var/www/html/public`, automatic fallback to `/var/www/html`).
   - Dynamic UID/GID: `PUID` and `PGID` variables (default `1000:1000` for `www-data`) to prevent host volume permission conflicts.
3. **Built-in Composer, Xdebug, ionCube Loader & SQLite Support**:
   - PHP 5.6–7.1: Composer 2.2 LTS.
   - PHP 7.2–8.5 & FrankenPHP: Latest Composer 2.x.
   - ionCube Loader pre-installed for supported upstream versions.
   - Xdebug installed across all PHP versions, disabled by default (`PHP_XDEBUG_ENABLED=0`) with on-the-fly activation (`PHP_XDEBUG_ENABLED=1`).
   - Built-in `pdo_sqlite` and `sqlite3` extensions across all PHP-FPM and FrankenPHP images for zero-overhead local databases.
4. **Web Servers & Ingress Proxies**:
   - Nginx (Mainline Alpine with HTTP/2, HTTP/3 QUIC, Brotli, WAF)
   - Apache (httpd 2.4 Alpine with event MPM, mod_proxy_fcgi, mod_rewrite, WAF)
   - OpenLiteSpeed (LSCache, HTTP/3 QUIC, WAF)
   - Caddy (Standalone v2 with Auto-HTTPS, HTTP/3, Zstd/Gzip, FastCGI PHP-FPM)
   - Lighttpd (Ultra-lightweight with FastCGI PHP-FPM, mod_deflate, WAF)
   - Traefik (Versions: 2.11 LTS, 3.1, 3.2, 3.3 with Docker Provider auto-discovery, Cloudflare Real-IP, HTTP/3, Dashboard)
5. **Network Databases & Caching**:
   - MySQL 8.0, 8.4 LTS
   - MariaDB 10.11, 11.4 LTS (Alpine)
   - PostgreSQL 16, 17 (Alpine)
   - Redis 7.2, 7.4 (Alpine) with LRU cache and AOF persistence
   - MongoDB 7.0, 8.0
6. **Networking & Security**:
   - Built-in support for `Traefik` (primary ingress) and `Cloudflare` Real IP (`CF-Connecting-IP`, `X-Forwarded-For`).
   - Built-in WAF rules (blocking `.env`, `.git`, path traversal attempts, enforcing hardened security headers).
7. **Verified Images Catalog**:
   - Only images and stack pairs that pass integration tests are published to `catalog.json` and `CATALOG.md`.
8. **Knowledge Base Management (For AI Agents Only)**:
   - The AI Agent directly accesses Obsidian MCP tools (`Projekty/WarpPanel/`) to synchronize architectural documentation.
