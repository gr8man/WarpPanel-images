FROM php:8.4.4-fpm-alpine3.21

LABEL maintainer="WarpPanel Team"
LABEL org.opencontainers.image.title="WarpPanel PHP-FPM 8.4 (Alpine)"
LABEL org.opencontainers.image.description="High-performance, lightweight PHP-FPM container with Composer for WarpPanel"
LABEL org.opencontainers.image.version="8.4"

# Install core system utilities & shadow (for dynamic usermod/groupmod)
RUN apk add --no-cache \
    bash \
    curl \
    ca-certificates \
    git \
    unzip \
    zip \
    msmtp \
    shadow \
    tzdata \
    icu-data-full

# Add official Docker PHP extension installer
COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

# Install standard WarpPanel hosting extensions
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

# Setup standard directory structure (serversideup standard)
ENV APP_DIR=/var/www/html \
    WEB_DOCUMENT_ROOT=/var/www/html/public \
    PUID=1000 \
    PGID=1000 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/tmp/composer \
    COMPOSER_MEMORY_LIMIT=-1

WORKDIR /var/www/html

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm", "-F"]
