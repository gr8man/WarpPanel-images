#!/bin/sh
set -e

PUID=${PUID:-1000}
PGID=${PGID:-1000}
WEB_DOCUMENT_ROOT=${WEB_DOCUMENT_ROOT:-/var/www/html/public}
CLIENT_MAX_BODY_SIZE=${CLIENT_MAX_BODY_SIZE:-64M}
FASTCGI_READ_TIMEOUT=${FASTCGI_READ_TIMEOUT:-60s}
PHP_FPM_HOST=${PHP_FPM_HOST:-php-fpm}
PHP_FPM_PORT=${PHP_FPM_PORT:-9000}
PHP_FPM_SOCKET=${PHP_FPM_SOCKET:-}
CLOUDFLARE_REAL_IP=${CLOUDFLARE_REAL_IP:-1}
TRUSTED_PROXIES=${TRUSTED_PROXIES:-"10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 127.0.0.1/32"}
SECURITY_WAF_ENABLED=${SECURITY_WAF_ENABLED:-1}

# 1. User & Group ID mapping for nginx user
if [ "$(id -u)" = "0" ]; then
    CURRENT_UID=$(id -u nginx 2>/dev/null || echo "")
    CURRENT_GID=$(id -g nginx 2>/dev/null || echo "")

    if [ -n "$CURRENT_GID" ] && [ "$CURRENT_GID" != "$PGID" ]; then
        groupmod -g "$PGID" nginx 2>/dev/null || sed -i -e "s/:$CURRENT_GID:/:$PGID:/" /etc/group || true
    fi

    if [ -n "$CURRENT_UID" ] && [ "$CURRENT_UID" != "$PUID" ]; then
        usermod -u "$PUID" -g "$PGID" nginx 2>/dev/null || sed -i -e "s/:$CURRENT_UID:$PGID:/:$PUID:$PGID:/" /etc/passwd || true
    fi
fi

# 2. DocumentRoot Fallback Check
if [ ! -d "$WEB_DOCUMENT_ROOT" ] && [ -d "/var/www/html" ]; then
    echo "[WarpPanel Nginx] Notice: Document root $WEB_DOCUMENT_ROOT does not exist, falling back to /var/www/html"
    RESOLVED_DOCUMENT_ROOT="/var/www/html"
else
    RESOLVED_DOCUMENT_ROOT="$WEB_DOCUMENT_ROOT"
fi

# 3. Determine PHP-FPM Backend (Unix socket or TCP)
if [ -n "$PHP_FPM_SOCKET" ] && [ -e "$PHP_FPM_SOCKET" ]; then
    PHP_FPM_BACKEND="unix:$PHP_FPM_SOCKET"
else
    PHP_FPM_BACKEND="$PHP_FPM_HOST:$PHP_FPM_PORT"
fi

# 4. Generate Real-IP Configuration (Cloudflare + Traefik/Docker subnets)
mkdir -p /etc/nginx/conf.d
REALIP_CONF="/etc/nginx/conf.d/00-realip.conf"

{
    echo "# Auto-generated Real-IP configuration"
    for proxy in $TRUSTED_PROXIES; do
        [ -n "$proxy" ] && echo "set_real_ip_from $proxy;"
    done

    if [ "$CLOUDFLARE_REAL_IP" = "1" ] && [ -f "/etc/nginx/cloudflare-ips.txt" ]; then
        grep -E '^[0-9a-fA-F:\.]+' /etc/nginx/cloudflare-ips.txt | while read -r ip; do
            echo "set_real_ip_from $ip;"
        done
        echo "real_ip_header CF-Connecting-IP;"
    else
        echo "real_ip_header X-Forwarded-For;"
    fi
    echo "real_ip_recursive on;"
} > "$REALIP_CONF"

# 5. Process default virtual host template
if [ "$SECURITY_WAF_ENABLED" = "1" ]; then
    WAF_INCLUDE_LINE="include /etc/nginx/waf-rules.conf;"
else
    WAF_INCLUDE_LINE="# WAF disabled"
fi

mkdir -p /etc/nginx/conf.d
sed -e "s|\${RESOLVED_DOCUMENT_ROOT}|$RESOLVED_DOCUMENT_ROOT|g" \
    -e "s|\${CLIENT_MAX_BODY_SIZE}|$CLIENT_MAX_BODY_SIZE|g" \
    -e "s|\${FASTCGI_READ_TIMEOUT}|$FASTCGI_READ_TIMEOUT|g" \
    -e "s|\${PHP_FPM_BACKEND}|$PHP_FPM_BACKEND|g" \
    -e "s|\${WAF_INCLUDE_LINE}|$WAF_INCLUDE_LINE|g" \
    /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

exec "$@"
