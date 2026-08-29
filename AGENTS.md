# WarpPanel Images — Instrukcje dla Agentów AI (AGENTS.md)

Projekt **WarpPanel-images** odpowiada za automatyczne generowanie, budowanie, testowanie i publikowanie zoptymalizowanych, lekkich obrazów kontenerowych (Alpine Linux) dla systemu hostingowego WarpPanel.

## Silnik Buildera (PHP & Composer)
Projekt wykorzystuje **PHP 8.1+ & Composer** (`symfony/yaml`, `twig/twig`) do parsowania macierzy i generowania szablonów.

### Dostępne Komendy Composera
- `composer generate` — generuje Dockerfile i docker-bake.hcl na podstawie `matrix.yaml` i szablonów Twig.
- `composer build` — buduje obrazy lokalnie za pomocą `docker buildx bake`.
- `composer test` — uruchamia automatyczne testy integracyjne stosu WWW + PHP-FPM / FrankenPHP / Baz Danych.
- `composer catalog` — aktualizuje katalog sprawdzonych obrazów (`catalog.json`, `CATALOG.md`).

## Zasady Architektury Obrazów
1. **Baza Alpine & Oficjalne Obrazy Docker Hub**: Wszystkie obrazy bazują na oficjalnych bazach Alpine Linux / Docker Hub (lekkie, bezpieczne, szybki start).
2. **Standard ścieżek wzorowany na `serversideup/php`**:
   - Domyślny katalog: `/var/www/html`
   - DocumentRoot: zmienna środowiskowa `WEB_DOCUMENT_ROOT` (domyślnie `/var/www/html/public`, fallback do `/var/www/html`).
   - Dynamiczny UID/GID: zmienne `PUID` i `PGID` (domyślnie `1000:1000` dla `www-data`), brak problemów z uprawnieniami na wolumenach hosta.
3. **Wbudowany Composer, Xdebug, ionCube Loader & Obsługa SQLite w kontenerach PHP**:
   - Wersje 5.6-7.1: Composer 2.2 LTS.
   - Wersje 7.2-8.5 oraz FrankenPHP: najnowszy Composer 2.x.
   - ionCube Loader wbudowany w standardzie dla wersji ze wsparciem upstream.
   - Xdebug zainstalowany we wszystkich wersjach, domyślnie wyłączony (`PHP_XDEBUG_ENABLED=0`) z możliwością włączenia w locie (`PHP_XDEBUG_ENABLED=1`).
   - Wbudowane rozszerzenia `pdo_sqlite` oraz `sqlite3` we wszystkich obrazach PHP-FPM i FrankenPHP (lokalny zapis plików bazodanowych).
4. **Wbudowane Serwery WWW i Reverse Proxy**:
   - Nginx (Mainline Alpine z HTTP/2, HTTP/3 QUIC, Brotli, WAF)
   - Apache (httpd 2.4 Alpine z event MPM, mod_proxy_fcgi, mod_rewrite, WAF)
   - OpenLiteSpeed (LSCache, HTTP/3 QUIC, WAF)
   - Caddy (Standalone v2 z Auto-HTTPS, HTTP/3, Zstd/Gzip, FastCGI PHP-FPM)
   - Lighttpd (Ultra-lightweight z FastCGI PHP-FPM, mod_deflate, WAF)
   - Traefik (Wersje: 2.11 LTS, 3.1, 3.2, 3.3 z Docker Provider auto-discovery, Cloudflare Real-IP, HTTP/3, Dashboard)
5. **Wbudowane Sieciowe Bazy Danych**:
   - MySQL 8.0, 8.4 LTS
   - MariaDB 10.11, 11.4 LTS (Alpine)
   - PostgreSQL 16, 17 (Alpine)
   - Redis 7.2, 7.4 (Alpine) z LRU cache i AOF persistence
   - MongoDB 7.0, 8.0
6. **Sieć & Bezpieczeństwo**:
   - Wbudowane wsparcie dla `Traefik` (główny load balancer) oraz `Cloudflare` Real IP (`CF-Connecting-IP`, `X-Forwarded-For`).
   - Wbudowana warstwa WAF i reguły ochronne (blokowanie `.env`, `.git`, ochrona przed atakami Path Traversal, bezpieczne nagłówki HTTP).
7. **Katalog Sprawdzonych Obrazów**:
   - Tylko obrazy, które pomyślnie przeszły testy integracyjne, trafiają do `catalog.json` oraz `CATALOG.md`.
7. **Zarządzanie Bazy Wiedzy (Tylko dla Modeli AI)**:
   - Agent AI korzysta bezpośrednio z narzędzi MCP Obsidian (`Projekty/WarpPanel/`) do aktualizacji bazy wiedzy. Repozytorium jest wolne od lokalnych zależności.
