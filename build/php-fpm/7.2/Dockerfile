FROM alpine:3.9

LABEL maintainer="WarpPanel Team"
LABEL org.opencontainers.image.title="WarpPanel PHP-FPM 7.2 Legacy (Alpine)"
LABEL org.opencontainers.image.description="Lightweight legacy PHP-FPM container for WarpPanel with Composer support"
LABEL org.opencontainers.image.version="7.2"

# Install core system utilities & shadow
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

# Install Composer (Composer 2.2 LTS supports PHP 5.3 - 7.2, Composer latest supports PHP 7.2.5+)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Setup standard directory structure (serversideup standard)
ENV APP_DIR=/var/www/html \
    WEB_DOCUMENT_ROOT=/var/www/html/public \
    PUID=1000 \
    PGID=1000 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/tmp/composer

WORKDIR /var/www/html

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm", "-F"]
