#!/bin/bash
set -e

# Dynamic UID/GID
if [ -n "$PUID" ] && [ "$PUID" != "0" ]; then
    usermod -u "$PUID" lighttpd >/dev/null 2>&1 || true
fi
if [ -n "$PGID" ] && [ "$PGID" != "0" ]; then
    groupmod -g "$PGID" lighttpd >/dev/null 2>&1 || true
fi

if [ -z "$WEB_DOCUMENT_ROOT" ] || [ ! -d "$WEB_DOCUMENT_ROOT" ]; then
    if [ -d "/var/www/html/public" ]; then
        export WEB_DOCUMENT_ROOT="/var/www/html/public"
    else
        export WEB_DOCUMENT_ROOT="/var/www/html"
    fi
fi

export PHP_FPM_HOST="${PHP_FPM_HOST:-php}"
export PHP_FPM_PORT="${PHP_FPM_PORT:-9000}"

exec "$@"
