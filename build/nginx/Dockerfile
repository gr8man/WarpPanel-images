FROM nginx:1.27-alpine

LABEL maintainer="WarpPanel Team"
LABEL org.opencontainers.image.title="WarpPanel Nginx Webserver (Alpine)"
LABEL org.opencontainers.image.description="High-performance Nginx with Traefik & Cloudflare Real-IP support, WAF rules and FastCGI integration"

RUN apk add --no-cache \
    bash \
    curl \
    ca-certificates \
    shadow \
    tzdata

# Setup standard directory structure (serversideup standard)
ENV APP_DIR=/var/www/html \
    WEB_DOCUMENT_ROOT=/var/www/html/public \
    PUID=1000 \
    PGID=1000 \
    PHP_FPM_HOST=php-fpm \
    PHP_FPM_PORT=9000 \
    CLOUDFLARE_REAL_IP=1 \
    TRUSTED_PROXIES="10.0.0.0/8 172.16.0.0/12 192.168.0.0/16 127.0.0.1/32" \
    SECURITY_WAF_ENABLED=1 \
    CLIENT_MAX_BODY_SIZE=64M \
    FASTCGI_READ_TIMEOUT=60s

WORKDIR /var/www/html

# Copy configuration templates & security rules
COPY nginx.conf /etc/nginx/nginx.conf
COPY default.conf.template /etc/nginx/templates/default.conf.template
COPY waf-rules.conf /etc/nginx/waf-rules.conf
COPY cloudflare-ips.txt /etc/nginx/cloudflare-ips.txt
COPY docker-entrypoint-nginx.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80 443

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]
