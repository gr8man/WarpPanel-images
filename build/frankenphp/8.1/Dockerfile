FROM dunglas/frankenphp:1-php8.1-alpine

LABEL maintainer="WarpPanel Team"
LABEL org.opencontainers.image.title="WarpPanel FrankenPHP 8.1 (Alpine)"
LABEL org.opencontainers.image.description="All-in-one high-performance PHP runtime with Caddy, HTTP/3, Worker Mode and Cloudflare Real-IP"

RUN apk add --no-cache \
    bash \
    curl \
    ca-certificates \
    git \
    unzip \
    zip \
    msmtp \
    shadow \
    tzdata

# Add official Docker PHP extension installer
COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

# Install standard WarpPanel extensions
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    bcmath \
    bz2 \
    calendar \
    exif \
    gd \
    gettext \
    gmp \
    igbinary \
    imagick \
    intl \
    mbstring \
    mysqli \
    opcache \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    redis \
    soap \
    sockets \
    sodium \
    sysvmsg \
    sysvsem \
    sysvshm \
    xsl \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Standard paths
ENV APP_DIR=/var/www/html \
    WEB_DOCUMENT_ROOT=/var/www/html/public \
    PUID=1000 \
    PGID=1000 \
    CADDY_GLOBAL_OPTIONS="" \
    FRANKENPHP_CONFIG="" \
    CLOUDFLARE_REAL_IP=1 \
    SECURITY_WAF_ENABLED=1

WORKDIR /var/www/html

COPY Caddyfile /etc/caddy/Caddyfile
COPY docker-entrypoint-frankenphp.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80 443 443/udp

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
