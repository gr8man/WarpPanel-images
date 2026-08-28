#!/bin/sh
set -e

PUID=${PUID:-1000}
PGID=${PGID:-1000}
WEB_DOCUMENT_ROOT=${WEB_DOCUMENT_ROOT:-/var/www/html/public}
PHP_FPM_HOST=${PHP_FPM_HOST:-php-fpm}
PHP_FPM_PORT=${PHP_FPM_PORT:-9000}
PHP_FPM_SOCKET=${PHP_FPM_SOCKET:-}
CLOUDFLARE_REAL_IP=${CLOUDFLARE_REAL_IP:-1}
TRUSTED_PROXIES=${TRUSTED_PROXIES:-"10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 127.0.0.1/32"}
SECURITY_WAF_ENABLED=${SECURITY_WAF_ENABLED:-1}

# 1. User & Group mapping
if [ "$(id -u)" = "0" ]; then
    CURRENT_UID=$(id -u daemon 2>/dev/null || echo "")
    CURRENT_GID=$(id -g daemon 2>/dev/null || echo "")

    if [ -n "$CURRENT_GID" ] && [ "$CURRENT_GID" != "$PGID" ]; then
        groupmod -g "$PGID" daemon 2>/dev/null || sed -i -e "s/:$CURRENT_GID:/:$PGID:/" /etc/group || true
    fi

    if [ -n "$CURRENT_UID" ] && [ "$CURRENT_UID" != "$PUID" ]; then
        usermod -u "$PUID" -g "$PGID" daemon 2>/dev/null || sed -i -e "s/:$CURRENT_UID:$PGID:/:$PUID:$PGID:/" /etc/passwd || true
    fi
fi

# 2. DocumentRoot Fallback Check
if [ ! -d "$WEB_DOCUMENT_ROOT" ] && [ -d "/var/www/html" ]; then
    echo "[WarpPanel Apache] Notice: Document root $WEB_DOCUMENT_ROOT does not exist, falling back to /var/www/html"
    RESOLVED_DOCUMENT_ROOT="/var/www/html"
else
    RESOLVED_DOCUMENT_ROOT="$WEB_DOCUMENT_ROOT"
fi

# 3. Determine PHP-FPM Backend
if [ -n "$PHP_FPM_SOCKET" ] && [ -e "$PHP_FPM_SOCKET" ]; then
    PHP_FPM_BACKEND="unix:$PHP_FPM_SOCKET|fcgi://localhost"
else
    PHP_FPM_BACKEND="$PHP_FPM_HOST:$PHP_FPM_PORT"
fi

# 4. Generate Real-IP Configuration for Apache mod_remoteip
mkdir -p /usr/local/apache2/conf/extra /usr/local/apache2/conf/conf.d
REALIP_CONF="/usr/local/apache2/conf/extra/realip.conf"

echo "# Auto-generated Apache RemoteIP Configuration" > "$REALIP_CONF"
if [ "$CLOUDFLARE_REAL_IP" = "1" ]; then
    echo "RemoteIPHeader CF-Connecting-IP" >> "$REALIP_CONF"
else
    echo "RemoteIPHeader X-Forwarded-For" >> "$REALIP_CONF"
fi

for proxy in $TRUSTED_PROXIES; do
    echo "RemoteIPInternalProxy $proxy" >> "$REALIP_CONF"
done

if [ "$CLOUDFLARE_REAL_IP" = "1" ] && [ -f "/usr/local/apache2/conf/extra/cloudflare-ips.txt" ]; then
    grep -v '^#' /usr/local/apache2/conf/extra/cloudflare-ips.txt | grep -v '^$' | while read -r ip; do
        echo "RemoteIPTrustedProxy $ip" >> "$REALIP_CONF"
    done
fi

# 5. Process Vhost Template
if [ "$SECURITY_WAF_ENABLED" = "1" ]; then
    WAF_INCLUDE_LINE="Include conf/extra/waf-rules.conf"
else
    WAF_INCLUDE_LINE="# WAF disabled"
fi

sed -e "s|\${RESOLVED_DOCUMENT_ROOT}|$RESOLVED_DOCUMENT_ROOT|g" \
    -e "s|\${PHP_FPM_BACKEND}|$PHP_FPM_BACKEND|g" \
    -e "s|\${WAF_INCLUDE_LINE}|$WAF_INCLUDE_LINE|g" \
    /usr/local/apache2/conf/extra/vhost.conf.template > /usr/local/apache2/conf/extra/vhost.conf

exec "$@"
