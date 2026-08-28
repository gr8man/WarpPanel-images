#!/bin/sh
set -e

# ==============================================================================
# WarpPanel Container Entrypoint (Serversideup Standard & Dynamic Config Engine)
# ==============================================================================

PUID=${PUID:-1000}
PGID=${PGID:-1000}
WEB_DOCUMENT_ROOT=${WEB_DOCUMENT_ROOT:-/var/www/html/public}
APP_DIR=${APP_DIR:-/var/www/html}
COMPOSER_AUTO_INSTALL=${COMPOSER_AUTO_INSTALL:-0}
COMPOSER_HOME=${COMPOSER_HOME:-/tmp/composer}
export COMPOSER_HOME
export COMPOSER_ALLOW_SUPERUSER=${COMPOSER_ALLOW_SUPERUSER:-1}
export COMPOSER_MEMORY_LIMIT=${COMPOSER_MEMORY_LIMIT:--1}

# 1. Dynamic User & Group ID mapping for www-data / app user
if [ "$(id -u)" = "0" ]; then
    CURRENT_UID=$(id -u www-data 2>/dev/null || echo "")
    CURRENT_GID=$(id -g www-data 2>/dev/null || echo "")

    if [ -n "$CURRENT_GID" ] && [ "$CURRENT_GID" != "$PGID" ]; then
        groupmod -g "$PGID" www-data 2>/dev/null || sed -i -e "s/:$CURRENT_GID:/:$PGID:/" /etc/group || true
    fi

    if [ -n "$CURRENT_UID" ] && [ "$CURRENT_UID" != "$PUID" ]; then
        usermod -u "$PUID" -g "$PGID" www-data 2>/dev/null || sed -i -e "s/:$CURRENT_UID:$PGID:/:$PUID:$PGID:/" /etc/passwd || true
    fi

    # Ensure runtime, composer, and log directories exist and have proper ownership
    mkdir -p /var/run/php /var/log/php-fpm /var/log/nginx /var/log/apache2 /tmp "$COMPOSER_HOME" /var/www/html
    chown -R "$PUID:$PGID" /var/run/php /var/log/php-fpm /tmp "$COMPOSER_HOME" 2>/dev/null || true
fi

# 2. Dynamic PHP Configuration Generation from Environment Variables
if command -v php >/dev/null 2>&1; then
    PHP_INI_DIR=$(php -r 'echo ini_get("cfg_file_path") ? dirname(ini_get("cfg_file_path")) : "/usr/local/etc/php/conf.d";' 2>/dev/null || echo "/usr/local/etc/php/conf.d")
    [ -d "/etc/php/conf.d" ] && PHP_INI_DIR="/etc/php/conf.d"
    [ -d "/etc/php83/conf.d" ] && PHP_INI_DIR="/etc/php83/conf.d"
    [ -d "/etc/php82/conf.d" ] && PHP_INI_DIR="/etc/php82/conf.d"
    [ -d "/etc/php81/conf.d" ] && PHP_INI_DIR="/etc/php81/conf.d"
    [ -d "/etc/php7/conf.d" ] && PHP_INI_DIR="/etc/php7/conf.d"
    [ -d "/etc/php5/conf.d" ] && PHP_INI_DIR="/etc/php5/conf.d"

    mkdir -p "$PHP_INI_DIR"
    WARPPANEL_INI="$PHP_INI_DIR/99-warppanel.ini"

    cat <<EOF > "$WARPPANEL_INI"
; --- WarpPanel Auto-generated PHP Configuration ---
memory_limit = ${PHP_MEMORY_LIMIT:-256M}
max_execution_time = ${PHP_MAX_EXECUTION_TIME:-60}
max_input_time = ${PHP_MAX_INPUT_TIME:-60}
upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE:-64M}
post_max_size = ${PHP_POST_MAX_SIZE:-64M}
max_input_vars = ${PHP_MAX_INPUT_VARS:-3000}
date.timezone = ${PHP_DATE_TIMEZONE:-UTC}
display_errors = ${PHP_DISPLAY_ERRORS:-Off}
display_startup_errors = ${PHP_DISPLAY_STARTUP_ERRORS:-Off}
error_reporting = ${PHP_ERROR_REPORTING:-E_ALL & ~E_DEPRECATED & ~E_STRICT}
log_errors = On
error_log = ${PHP_ERROR_LOG:-/proc/self/fd/2}
expose_php = ${PHP_EXPOSE_PHP:-Off}
variables_order = "EGPCS"

; OPcache Settings
opcache.enable = ${PHP_OPCACHE_ENABLE:-1}
opcache.enable_cli = ${PHP_OPCACHE_ENABLE_CLI:-0}
opcache.memory_consumption = ${PHP_OPCACHE_MEMORY_CONSUMPTION:-128}
opcache.interned_strings_buffer = ${PHP_OPCACHE_INTERNED_STRINGS_buffer:-16}
opcache.max_accelerated_files = ${PHP_OPCACHE_MAX_ACCELERATED_FILES:-10000}
opcache.validate_timestamps = ${PHP_OPCACHE_VALIDATE_TIMESTAMPS:-1}
opcache.revalidate_freq = ${PHP_OPCACHE_REVALIDATE_FREQ:-2}
opcache.save_comments = 1
opcache.fast_shutdown = 1
${PHP_OPCACHE_JIT:+opcache.jit = ${PHP_OPCACHE_JIT}}
${PHP_OPCACHE_JIT_BUFFER_SIZE:+opcache.jit_buffer_size = ${PHP_OPCACHE_JIT_BUFFER_SIZE}}
EOF
fi

# 3. Dynamic PHP-FPM Pool Configuration Generation
if [ -d "/usr/local/etc/php-fpm.d" ] || [ -d "/etc/php-fpm.d" ] || [ -d "/etc/php83/php-fpm.d" ] || [ -d "/etc/php7/php-fpm.d" ]; then
    FPM_DIR="/usr/local/etc/php-fpm.d"
    [ -d "/etc/php-fpm.d" ] && FPM_DIR="/etc/php-fpm.d"
    [ -d "/etc/php83/php-fpm.d" ] && FPM_DIR="/etc/php83/php-fpm.d"
    [ -d "/etc/php7/php-fpm.d" ] && FPM_DIR="/etc/php7/php-fpm.d"
    [ -d "/etc/php5/php-fpm.d" ] && FPM_DIR="/etc/php5/php-fpm.d"

    cat <<EOF > "$FPM_DIR/zz-warppanel-pool.conf"
[www]
user = www-data
group = www-data
listen = ${FPM_LISTEN:-9000}
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = ${FPM_PM_TYPE:-dynamic}
pm.max_children = ${FPM_PM_MAX_CHILDREN:-20}
pm.start_servers = ${FPM_PM_START_SERVERS:-4}
pm.min_spare_servers = ${FPM_PM_MIN_SPARE_SERVERS:-2}
pm.max_spare_servers = ${FPM_PM_MAX_SPARE_SERVERS:-8}
pm.max_requests = ${FPM_PM_MAX_REQUESTS:-500}
pm.status_path = /fpm-status
ping.path = /fpm-ping

request_terminate_timeout = ${FPM_REQUEST_TERMINATE_TIMEOUT:-60s}
catch_workers_output = yes
decorate_workers_output = no
clear_env = no
EOF
fi

# 4. Fallback verification for DocumentRoot (fallback to /var/www/html if public directory does not exist)
if [ ! -d "$WEB_DOCUMENT_ROOT" ] && [ -d "/var/www/html" ]; then
    echo "[WarpPanel] Notice: Document root $WEB_DOCUMENT_ROOT does not exist, falling back to /var/www/html"
    export RESOLVED_DOCUMENT_ROOT="/var/www/html"
else
    export RESOLVED_DOCUMENT_ROOT="$WEB_DOCUMENT_ROOT"
fi

# 5. Optional Composer Auto-Install
if [ "$COMPOSER_AUTO_INSTALL" = "1" ] && [ -f "$APP_DIR/composer.json" ] && command -v composer >/dev/null 2>&1; then
    echo "[WarpPanel] Running automatic 'composer install' in $APP_DIR..."
    (cd "$APP_DIR" && composer install --no-interaction --prefer-dist --optimize-autoloader) || true
fi

# Execute passed CMD
exec "$@"
