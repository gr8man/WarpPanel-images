# WarpPanel Images 🚀

Automatyczny system generowania, budowania, testowania i publikowania zoptymalizowanych, lekkich obrazów kontenerowych (**Alpine Linux**) dla panelu hostingowego **WarpPanel**.

---

## 🌟 Kluczowe Cechy

- **Baza Alpine**: Ultra-lekkie kontenery (kilkadziesiąt MB zamiast kilkuset MB).
- **Szerokie wsparcie PHP**: PHP 5.6 – 8.5 (FPM) zoptymalizowane pod hosting.
- **Wielowariantowość Webserwerów**: Nginx, Apache HTTPD (`mpm_event` + `mod_proxy_fcgi`), OpenLiteSpeed oraz nowoczesny runtime FrankenPHP (Caddy + PHP z obsługą Worker Mode).
- **Standard ścieżek `serversideup`**:
  - Aplikacje w `/var/www/html`
  - Konfigurowalny `WEB_DOCUMENT_ROOT` (domyślnie `/var/www/html/public`, automatyczny fallback do `/var/www/html`)
  - Dynamiczny `PUID` / `PGID` w `docker-entrypoint.sh` bez problemów z uprawnieniami do wolumenów.
- **Traefik + Cloudflare Real IP**: Automatyczne pobieranie rzeczywistego IP klienta z nagłówków `CF-Connecting-IP` / `X-Forwarded-For`.
- **Wbudowany WAF**: Ochrona przed skanowaniem i exploitami, automatyczna blokada plików `.env`, `.git`, `.sql` oraz ochrona nagłówków HTTP.
- **Pełna Konfigurowalność przez zmienne środowiskowe i pliki**: Każdy parametr PHP/FPM/Nginx/Apache można nadpisać przez ENV lub pliki `conf.d`.
- **Automatyczny Katalog Sprawdzonych Obrazów**: Publikacja w `catalog.json` i `CATALOG.md` tylko po pomyślnym przejściu testów integracyjnych.

---

## 📦 Szybki Start

### 1. Wygenerowanie wszystkich Dockerfile i konfiguracji
```bash
make generate
```

### 2. Budowanie równoległe za pomocą `docker buildx bake`
```bash
make build
```

### 3. Uruchomienie automatycznych testów integracyjnych
```bash
make test
```

---

## 📋 Lista Dostępnych Obrazów

Zobacz pełny, automatycznie generowany [CATALOG.md](CATALOG.md) oraz [catalog.json](catalog.json).

---

## 🔧 Zmienne Środowiskowe

Szczegółowy opis wszystkich zmiennych środowiskowych i reguł bezpieczeństwa znajduje się w pliku [AGENTS.md](AGENTS.md) oraz w dokumentacji Obsidian (`Projekty/WarpPanel/Zmienne Środowiskowe.md`).
