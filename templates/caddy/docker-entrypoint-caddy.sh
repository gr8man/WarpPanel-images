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
if [ ! -d "$WEB_DOCUMENT_ROOT" ] && [ -d "/var/www/html" ]; then
    export WEB_DOCUMENT_ROOT="/var/www/html"
fi

exec "$@"
