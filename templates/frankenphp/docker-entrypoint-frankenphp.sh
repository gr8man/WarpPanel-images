#!/bin/sh
set -e

PUID=${PUID:-1000}
PGID=${PGID:-1000}
WEB_DOCUMENT_ROOT=${WEB_DOCUMENT_ROOT:-/var/www/html/public}
APP_DIR=${APP_DIR:-/var/www/html}
CLOUDFLARE_REAL_IP=${CLOUDFLARE_REAL_IP:-1}
export TRUSTED_PROXIES=${TRUSTED_PROXIES:-"10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 127.0.0.1/32"}

COMPOSER_AUTO_INSTALL=${COMPOSER_AUTO_INSTALL:-0}
COMPOSER_HOME=${COMPOSER_HOME:-/tmp/composer}
export COMPOSER_HOME
export COMPOSER_ALLOW_SUPERUSER=${COMPOSER_ALLOW_SUPERUSER:-1}
export COMPOSER_MEMORY_LIMIT=${COMPOSER_MEMORY_LIMIT:--1}

if [ "$CLOUDFLARE_REAL_IP" = "1" ]; then
    export REAL_IP_HEADER="CF-Connecting-IP"
else
    export REAL_IP_HEADER="X-Forwarded-For"
fi

# Dynamic user mapping
if [ "$(id -u)" = "0" ]; then
    CURRENT_UID=$(id -u www-data 2>/dev/null || echo "")
    CURRENT_GID=$(id -g www-data 2>/dev/null || echo "")

    if [ -n "$CURRENT_GID" ] && [ "$CURRENT_GID" != "$PGID" ]; then
        groupmod -g "$PGID" www-data 2>/dev/null || sed -i -e "s/:$CURRENT_GID:/:$PGID:/" /etc/group || true
    fi

    if [ -n "$CURRENT_UID" ] && [ "$CURRENT_UID" != "$PUID" ]; then
        usermod -u "$PUID" -g "$PGID" www-data 2>/dev/null || sed -i -e "s/:$CURRENT_UID:$PGID:/:$PUID:$PGID:/" /etc/passwd || true
    fi

    mkdir -p /var/log/frankenphp /tmp "$COMPOSER_HOME" /var/www/html
    chown -R "$PUID:$PGID" /var/log/frankenphp /tmp "$COMPOSER_HOME" 2>/dev/null || true
fi

# Generate PHP INI
PHP_INI_DIR=$(php -r 'echo ini_get("cfg_file_path") ? dirname(ini_get("cfg_file_path")) : "/usr/local/etc/php/conf.d";' 2>/dev/null || echo "/usr/local/etc/php/conf.d")
mkdir -p "$PHP_INI_DIR"
cat <<EOF > "$PHP_INI_DIR/99-warppanel.ini"
memory_limit = ${PHP_MEMORY_LIMIT:-256M}
max_execution_time = ${PHP_MAX_EXECUTION_TIME:-60}
upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE:-64M}
post_max_size = ${PHP_POST_MAX_SIZE:-64M}
max_input_vars = ${PHP_MAX_INPUT_VARS:-3000}
date.timezone = ${PHP_DATE_TIMEZONE:-UTC}
display_errors = ${PHP_DISPLAY_ERRORS:-Off}
expose_php = ${PHP_EXPOSE_PHP:-Off}
opcache.enable = ${PHP_OPCACHE_ENABLE:-1}
opcache.enable_cli = 1
opcache.memory_consumption = ${PHP_OPCACHE_MEMORY_CONSUMPTION:-128}
opcache.max_accelerated_files = ${PHP_OPCACHE_MAX_ACCELERATED_FILES:-10000}
opcache.validate_timestamps = ${PHP_OPCACHE_VALIDATE_TIMESTAMPS:-1}
EOF

# Fallback check
if [ ! -d "$WEB_DOCUMENT_ROOT" ] && [ -d "/var/www/html" ]; then
    echo "[WarpPanel FrankenPHP] Notice: Document root $WEB_DOCUMENT_ROOT does not exist, falling back to /var/www/html"
    export RESOLVED_DOCUMENT_ROOT="/var/www/html"
else
    export RESOLVED_DOCUMENT_ROOT="$WEB_DOCUMENT_ROOT"
fi

# Optional Composer Auto-Install
if [ "$COMPOSER_AUTO_INSTALL" = "1" ] && [ -f "$APP_DIR/composer.json" ] && command -v composer >/dev/null 2>&1; then
    echo "[WarpPanel FrankenPHP] Running automatic 'composer install' in $APP_DIR..."
    (cd "$APP_DIR" && composer install --no-interaction --prefer-dist --optimize-autoloader) || true
fi

exec "$@"
