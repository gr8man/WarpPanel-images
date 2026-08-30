#!/bin/bash
set -e

# Dynamic UID/GID adjustment
if [ -n "$PUID" ] && [ "$PUID" != "0" ]; then
    if id -u www-data >/dev/null 2>&1; then
        usermod -u "$PUID" www-data >/dev/null 2>&1 || true
    fi
fi
if [ -n "$PGID" ] && [ "$PGID" != "0" ]; then
    if getent group www-data >/dev/null 2>&1; then
        groupmod -g "$PGID" www-data >/dev/null 2>&1 || true
    fi
fi

# Fallback WEB_DOCUMENT_ROOT
if [ -z "$WEB_DOCUMENT_ROOT" ] || [ ! -d "$WEB_DOCUMENT_ROOT" ]; then
    if [ -d "/var/www/html/public" ]; then
        export WEB_DOCUMENT_ROOT="/var/www/html/public"
    else
        export WEB_DOCUMENT_ROOT="/var/www/html"
    fi
fi

if [ -n "$PHP_FPM_HOST" ]; then
    PHP_FPM_PORT=${PHP_FPM_PORT:-9000}
    export PHP_FPM_UPSTREAM="$PHP_FPM_HOST:$PHP_FPM_PORT"
elif [ -z "$PHP_FPM_UPSTREAM" ]; then
    export PHP_FPM_UPSTREAM="php-fpm:9000"
fi

exec "$@"
