# WarpPanel Images — Instrukcje dla Agentów AI (AGENTS.md)

Projekt **WarpPanel-images** odpowiada za automatyczne generowanie, budowanie, testowanie i publikowanie zoptymalizowanych, lekkich obrazów kontenerowych (Alpine Linux) dla systemu hostingowego WarpPanel.

## Silnik Buildera (PHP & Composer)
Projekt wykorzystuje **PHP 8.1+ & Composer** (`symfony/yaml`, `twig/twig`) do parsowania macierzy i generowania szablonów.

### Dostępne Komendy Composera
- `composer generate` — generuje Dockerfile i docker-bake.hcl na podstawie `matrix.yaml` i szablonów Twig.
- `composer build` — buduje obrazy lokalnie za pomocą `docker buildx bake`.
- `composer test` — uruchamia automatyczne testy integracyjne stosu WWW + PHP-FPM / FrankenPHP.
- `composer catalog` — aktualizuje katalog sprawdzonych obrazów (`catalog.json`, `CATALOG.md`).
- `composer sync-obsidian` — synchronizuje stan dokumentacji z Obsidianem.

## Zasady Architektury Obrazów
1. **Baza Alpine**: Wszystkie obrazy bazują na Alpine Linux (lekkie, bezpieczne, szybki start).
2. **Standard ścieżek wzorowany na `serversideup/php`**:
   - Domyślny katalog: `/var/www/html`
   - DocumentRoot: zmienna środowiskowa `WEB_DOCUMENT_ROOT` (domyślnie `/var/www/html/public`, fallback do `/var/www/html`).
   - Dynamiczny UID/GID: zmienne `PUID` i `PGID` (domyślnie `1000:1000` dla `www-data`), brak problemów z uprawnieniami na wolumenach hosta.
3. **Wbudowany Composer w kontenerach**:
   - Wersje 5.6-7.1: Composer 2.2 LTS.
   - Wersje 7.2-8.5 oraz FrankenPHP: najnowszy Composer 2.x.
   - Zmienne: `COMPOSER_ALLOW_SUPERUSER=1`, `COMPOSER_HOME=/tmp/composer`, `COMPOSER_MEMORY_LIMIT=-1`, opcjonalne `COMPOSER_AUTO_INSTALL=1`.
4. **Pełna Konfigurowalność**:
   - Wszystkie parametry PHP, FPM, Apache, Nginx, FrankenPHP, OpenLiteSpeed konfigurowalne przez zmienne `ENV` oraz przez montowanie plików `.ini` / `.conf`.
5. **Sieć & Bezpieczeństwo**:
   - Wbudowane wsparcie dla `Traefik` (główny load balancer) oraz `Cloudflare` Real IP (`CF-Connecting-IP`, `X-Forwarded-For`).
   - Wbudowana warstwa WAF i reguły ochronne (blokowanie `.env`, `.git`, ochrona przed atakami Path Traversal, bezpieczne nagłówki HTTP).
6. **Katalog Sprawdzonych Obrazów**:
   - Tylko obrazy, które pomyślnie przeszły testy integracyjne, trafiają do `catalog.json` oraz `CATALOG.md`.
7. **Automatyczna synchronizacja z Obsidianem**:
   - Dokumentacja i katalog są aktualizowane w notatkach Obsidian (`Projekty/WarpPanel/`).
