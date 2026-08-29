group "default" {
    targets = ["php-fpm-8_0","php-fpm-8_1","php-fpm-8_2","php-fpm-8_3","php-fpm-8_4","php-fpm-8_5","php-fpm-5_6","php-fpm-7_0","php-fpm-7_1","php-fpm-7_2","php-fpm-7_3","php-fpm-7_4","frankenphp-8_2","frankenphp-8_3","frankenphp-8_4","frankenphp-8_5","nginx","apache","openlitespeed","caddy","lighttpd","traefik-v2_11","traefik-v3_1","traefik-v3_2","traefik-v3_3","mysql-8_4","mysql-8_0","mariadb-11_4","mariadb-10_11","postgres-17","postgres-16","redis-7_4","redis-7_2","mongodb-7_0","mongodb-8_0"]
}

group "php" {
    targets = ["php-fpm-8_0","php-fpm-8_1","php-fpm-8_2","php-fpm-8_3","php-fpm-8_4","php-fpm-8_5","php-fpm-5_6","php-fpm-7_0","php-fpm-7_1","php-fpm-7_2","php-fpm-7_3","php-fpm-7_4"]
}

group "frankenphp" {
    targets = ["frankenphp-8_2","frankenphp-8_3","frankenphp-8_4","frankenphp-8_5"]
}

group "traefik" {
    targets = ["traefik-v2_11","traefik-v3_1","traefik-v3_2","traefik-v3_3"]
}

group "webservers" {
    targets = ["nginx","apache","openlitespeed","caddy","lighttpd"]
}

group "databases" {
    targets = ["mysql-8_4","mysql-8_0","mariadb-11_4","mariadb-10_11","postgres-17","postgres-16","redis-7_4","redis-7_2","mongodb-7_0","mongodb-8_0"]
}

target "php-fpm-8_0" {
    context = "./build/php-fpm/8.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.0-fpm-alpine","ghcr.io/warppanel/php:8.0-fpm-alpine-20260829","ghcr.io/warppanel/php:8.0-fpm-alpine-current","ghcr.io/warppanel/php:8.0-fpm","ghcr.io/warppanel/php:8.0-fpm-20260829","ghcr.io/warppanel/php:8.0-fpm-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_1" {
    context = "./build/php-fpm/8.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.1-fpm-alpine","ghcr.io/warppanel/php:8.1-fpm-alpine-20260829","ghcr.io/warppanel/php:8.1-fpm-alpine-current","ghcr.io/warppanel/php:8.1-fpm","ghcr.io/warppanel/php:8.1-fpm-20260829","ghcr.io/warppanel/php:8.1-fpm-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_2" {
    context = "./build/php-fpm/8.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.2-fpm-alpine","ghcr.io/warppanel/php:8.2-fpm-alpine-20260829","ghcr.io/warppanel/php:8.2-fpm-alpine-current","ghcr.io/warppanel/php:8.2-fpm","ghcr.io/warppanel/php:8.2-fpm-20260829","ghcr.io/warppanel/php:8.2-fpm-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_3" {
    context = "./build/php-fpm/8.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.3-fpm-alpine","ghcr.io/warppanel/php:8.3-fpm-alpine-20260829","ghcr.io/warppanel/php:8.3-fpm-alpine-current","ghcr.io/warppanel/php:8.3-fpm","ghcr.io/warppanel/php:8.3-fpm-20260829","ghcr.io/warppanel/php:8.3-fpm-current","ghcr.io/warppanel/php:latest-fpm","ghcr.io/warppanel/php:latest-fpm-20260829","ghcr.io/warppanel/php:latest-fpm-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_4" {
    context = "./build/php-fpm/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.4-fpm-alpine","ghcr.io/warppanel/php:8.4-fpm-alpine-20260829","ghcr.io/warppanel/php:8.4-fpm-alpine-current","ghcr.io/warppanel/php:8.4-fpm","ghcr.io/warppanel/php:8.4-fpm-20260829","ghcr.io/warppanel/php:8.4-fpm-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_5" {
    context = "./build/php-fpm/8.5"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.5-fpm-alpine","ghcr.io/warppanel/php:8.5-fpm-alpine-20260829","ghcr.io/warppanel/php:8.5-fpm-alpine-current","ghcr.io/warppanel/php:8.5-fpm-dev","ghcr.io/warppanel/php:8.5-fpm-dev-20260829","ghcr.io/warppanel/php:8.5-fpm-dev-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-5_6" {
    context = "./build/php-fpm/5.6"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:5.6-fpm-alpine","ghcr.io/warppanel/php:5.6-fpm-alpine-20260829","ghcr.io/warppanel/php:5.6-fpm-alpine-current","ghcr.io/warppanel/php:5.6-fpm","ghcr.io/warppanel/php:5.6-fpm-20260829","ghcr.io/warppanel/php:5.6-fpm-current"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_0" {
    context = "./build/php-fpm/7.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.0-fpm-alpine","ghcr.io/warppanel/php:7.0-fpm-alpine-20260829","ghcr.io/warppanel/php:7.0-fpm-alpine-current","ghcr.io/warppanel/php:7.0-fpm","ghcr.io/warppanel/php:7.0-fpm-20260829","ghcr.io/warppanel/php:7.0-fpm-current"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_1" {
    context = "./build/php-fpm/7.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.1-fpm-alpine","ghcr.io/warppanel/php:7.1-fpm-alpine-20260829","ghcr.io/warppanel/php:7.1-fpm-alpine-current","ghcr.io/warppanel/php:7.1-fpm","ghcr.io/warppanel/php:7.1-fpm-20260829","ghcr.io/warppanel/php:7.1-fpm-current"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_2" {
    context = "./build/php-fpm/7.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.2-fpm-alpine","ghcr.io/warppanel/php:7.2-fpm-alpine-20260829","ghcr.io/warppanel/php:7.2-fpm-alpine-current","ghcr.io/warppanel/php:7.2-fpm","ghcr.io/warppanel/php:7.2-fpm-20260829","ghcr.io/warppanel/php:7.2-fpm-current"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_3" {
    context = "./build/php-fpm/7.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.3-fpm-alpine","ghcr.io/warppanel/php:7.3-fpm-alpine-20260829","ghcr.io/warppanel/php:7.3-fpm-alpine-current","ghcr.io/warppanel/php:7.3-fpm","ghcr.io/warppanel/php:7.3-fpm-20260829","ghcr.io/warppanel/php:7.3-fpm-current"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_4" {
    context = "./build/php-fpm/7.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.4-fpm-alpine","ghcr.io/warppanel/php:7.4-fpm-alpine-20260829","ghcr.io/warppanel/php:7.4-fpm-alpine-current","ghcr.io/warppanel/php:7.4-fpm","ghcr.io/warppanel/php:7.4-fpm-20260829","ghcr.io/warppanel/php:7.4-fpm-current"]
    platforms = ["linux/amd64"]
}

target "frankenphp-8_2" {
    context = "./build/frankenphp/8.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:frankenphp-8.2-alpine","ghcr.io/warppanel/frankenphp:frankenphp-8.2-alpine-20260829","ghcr.io/warppanel/frankenphp:frankenphp-8.2-alpine-current","ghcr.io/warppanel/frankenphp:frankenphp-8.2","ghcr.io/warppanel/frankenphp:frankenphp-8.2-20260829","ghcr.io/warppanel/frankenphp:frankenphp-8.2-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_3" {
    context = "./build/frankenphp/8.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:frankenphp-8.3-alpine","ghcr.io/warppanel/frankenphp:frankenphp-8.3-alpine-20260829","ghcr.io/warppanel/frankenphp:frankenphp-8.3-alpine-current","ghcr.io/warppanel/frankenphp:frankenphp-8.3","ghcr.io/warppanel/frankenphp:frankenphp-8.3-20260829","ghcr.io/warppanel/frankenphp:frankenphp-8.3-current","ghcr.io/warppanel/frankenphp:frankenphp-latest","ghcr.io/warppanel/frankenphp:frankenphp-latest-20260829","ghcr.io/warppanel/frankenphp:frankenphp-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_4" {
    context = "./build/frankenphp/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:frankenphp-8.4-alpine","ghcr.io/warppanel/frankenphp:frankenphp-8.4-alpine-20260829","ghcr.io/warppanel/frankenphp:frankenphp-8.4-alpine-current","ghcr.io/warppanel/frankenphp:frankenphp-8.4","ghcr.io/warppanel/frankenphp:frankenphp-8.4-20260829","ghcr.io/warppanel/frankenphp:frankenphp-8.4-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_5" {
    context = "./build/frankenphp/8.5"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:frankenphp-8.5-alpine","ghcr.io/warppanel/frankenphp:frankenphp-8.5-alpine-20260829","ghcr.io/warppanel/frankenphp:frankenphp-8.5-alpine-current","ghcr.io/warppanel/frankenphp:frankenphp-dev","ghcr.io/warppanel/frankenphp:frankenphp-dev-20260829","ghcr.io/warppanel/frankenphp:frankenphp-dev-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "nginx" {
    context = "./build/nginx"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/nginx:nginx-alpine","ghcr.io/warppanel/nginx:nginx-alpine-20260829","ghcr.io/warppanel/nginx:nginx-alpine-current","ghcr.io/warppanel/nginx:nginx-latest","ghcr.io/warppanel/nginx:nginx-latest-20260829","ghcr.io/warppanel/nginx:nginx-latest-current","ghcr.io/warppanel/nginx:nginx-1.27","ghcr.io/warppanel/nginx:nginx-1.27-20260829","ghcr.io/warppanel/nginx:nginx-1.27-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "apache" {
    context = "./build/apache"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/apache:apache-alpine","ghcr.io/warppanel/apache:apache-alpine-20260829","ghcr.io/warppanel/apache:apache-alpine-current","ghcr.io/warppanel/apache:apache-latest","ghcr.io/warppanel/apache:apache-latest-20260829","ghcr.io/warppanel/apache:apache-latest-current","ghcr.io/warppanel/apache:httpd-2.4","ghcr.io/warppanel/apache:httpd-2.4-20260829","ghcr.io/warppanel/apache:httpd-2.4-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "openlitespeed" {
    context = "./build/openlitespeed"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/openlitespeed:openlitespeed-alpine","ghcr.io/warppanel/openlitespeed:openlitespeed-alpine-20260829","ghcr.io/warppanel/openlitespeed:openlitespeed-alpine-current","ghcr.io/warppanel/openlitespeed:openlitespeed-latest","ghcr.io/warppanel/openlitespeed:openlitespeed-latest-20260829","ghcr.io/warppanel/openlitespeed:openlitespeed-latest-current","ghcr.io/warppanel/openlitespeed:ols-latest","ghcr.io/warppanel/openlitespeed:ols-latest-20260829","ghcr.io/warppanel/openlitespeed:ols-latest-current"]
    platforms = ["linux/amd64"]
}

target "caddy" {
    context = "./build/caddy"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/caddy:caddy-alpine","ghcr.io/warppanel/caddy:caddy-alpine-20260829","ghcr.io/warppanel/caddy:caddy-alpine-current","ghcr.io/warppanel/caddy:caddy-latest","ghcr.io/warppanel/caddy:caddy-latest-20260829","ghcr.io/warppanel/caddy:caddy-latest-current","ghcr.io/warppanel/caddy:caddy-2.8","ghcr.io/warppanel/caddy:caddy-2.8-20260829","ghcr.io/warppanel/caddy:caddy-2.8-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "lighttpd" {
    context = "./build/lighttpd"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/lighttpd:lighttpd-alpine","ghcr.io/warppanel/lighttpd:lighttpd-alpine-20260829","ghcr.io/warppanel/lighttpd:lighttpd-alpine-current","ghcr.io/warppanel/lighttpd:lighttpd-latest","ghcr.io/warppanel/lighttpd:lighttpd-latest-20260829","ghcr.io/warppanel/lighttpd:lighttpd-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v2_11" {
    context = "./build/traefik/2.11"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/traefik:traefik-2.11","ghcr.io/warppanel/traefik:traefik-2.11-20260829","ghcr.io/warppanel/traefik:traefik-2.11-current","ghcr.io/warppanel/traefik:traefik-v2.11","ghcr.io/warppanel/traefik:traefik-v2.11-20260829","ghcr.io/warppanel/traefik:traefik-v2.11-current","ghcr.io/warppanel/traefik:traefik-v2","ghcr.io/warppanel/traefik:traefik-v2-20260829","ghcr.io/warppanel/traefik:traefik-v2-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v3_1" {
    context = "./build/traefik/3.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/traefik:traefik-3.1","ghcr.io/warppanel/traefik:traefik-3.1-20260829","ghcr.io/warppanel/traefik:traefik-3.1-current","ghcr.io/warppanel/traefik:traefik-v3.1","ghcr.io/warppanel/traefik:traefik-v3.1-20260829","ghcr.io/warppanel/traefik:traefik-v3.1-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v3_2" {
    context = "./build/traefik/3.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/traefik:traefik-3.2","ghcr.io/warppanel/traefik:traefik-3.2-20260829","ghcr.io/warppanel/traefik:traefik-3.2-current","ghcr.io/warppanel/traefik:traefik-v3.2","ghcr.io/warppanel/traefik:traefik-v3.2-20260829","ghcr.io/warppanel/traefik:traefik-v3.2-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v3_3" {
    context = "./build/traefik/3.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/traefik:traefik-3.3","ghcr.io/warppanel/traefik:traefik-3.3-20260829","ghcr.io/warppanel/traefik:traefik-3.3-current","ghcr.io/warppanel/traefik:traefik-v3.3","ghcr.io/warppanel/traefik:traefik-v3.3-20260829","ghcr.io/warppanel/traefik:traefik-v3.3-current","ghcr.io/warppanel/traefik:traefik-v3","ghcr.io/warppanel/traefik:traefik-v3-20260829","ghcr.io/warppanel/traefik:traefik-v3-current","ghcr.io/warppanel/traefik:traefik-latest","ghcr.io/warppanel/traefik:traefik-latest-20260829","ghcr.io/warppanel/traefik:traefik-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mysql-8_4" {
    context = "./build/databases/mysql/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mysql:mysql-8.4","ghcr.io/warppanel/mysql:mysql-8.4-20260829","ghcr.io/warppanel/mysql:mysql-8.4-current","ghcr.io/warppanel/mysql:mysql-lts","ghcr.io/warppanel/mysql:mysql-lts-20260829","ghcr.io/warppanel/mysql:mysql-lts-current","ghcr.io/warppanel/mysql:mysql-latest","ghcr.io/warppanel/mysql:mysql-latest-20260829","ghcr.io/warppanel/mysql:mysql-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mysql-8_0" {
    context = "./build/databases/mysql/8.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mysql:mysql-8.0","ghcr.io/warppanel/mysql:mysql-8.0-20260829","ghcr.io/warppanel/mysql:mysql-8.0-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mariadb-11_4" {
    context = "./build/databases/mariadb/11.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mariadb:mariadb-11.4","ghcr.io/warppanel/mariadb:mariadb-11.4-20260829","ghcr.io/warppanel/mariadb:mariadb-11.4-current","ghcr.io/warppanel/mariadb:mariadb-lts","ghcr.io/warppanel/mariadb:mariadb-lts-20260829","ghcr.io/warppanel/mariadb:mariadb-lts-current","ghcr.io/warppanel/mariadb:mariadb-latest","ghcr.io/warppanel/mariadb:mariadb-latest-20260829","ghcr.io/warppanel/mariadb:mariadb-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mariadb-10_11" {
    context = "./build/databases/mariadb/10.11"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mariadb:mariadb-10.11","ghcr.io/warppanel/mariadb:mariadb-10.11-20260829","ghcr.io/warppanel/mariadb:mariadb-10.11-current","ghcr.io/warppanel/mariadb:mariadb-10.11-lts","ghcr.io/warppanel/mariadb:mariadb-10.11-lts-20260829","ghcr.io/warppanel/mariadb:mariadb-10.11-lts-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "postgres-17" {
    context = "./build/databases/postgres/17"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/postgres:postgres-17-alpine","ghcr.io/warppanel/postgres:postgres-17-alpine-20260829","ghcr.io/warppanel/postgres:postgres-17-alpine-current","ghcr.io/warppanel/postgres:postgres-17","ghcr.io/warppanel/postgres:postgres-17-20260829","ghcr.io/warppanel/postgres:postgres-17-current","ghcr.io/warppanel/postgres:postgres-latest","ghcr.io/warppanel/postgres:postgres-latest-20260829","ghcr.io/warppanel/postgres:postgres-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "postgres-16" {
    context = "./build/databases/postgres/16"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/postgres:postgres-16-alpine","ghcr.io/warppanel/postgres:postgres-16-alpine-20260829","ghcr.io/warppanel/postgres:postgres-16-alpine-current","ghcr.io/warppanel/postgres:postgres-16","ghcr.io/warppanel/postgres:postgres-16-20260829","ghcr.io/warppanel/postgres:postgres-16-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "redis-7_4" {
    context = "./build/databases/redis/7.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/redis:redis-7.4-alpine","ghcr.io/warppanel/redis:redis-7.4-alpine-20260829","ghcr.io/warppanel/redis:redis-7.4-alpine-current","ghcr.io/warppanel/redis:redis-7.4","ghcr.io/warppanel/redis:redis-7.4-20260829","ghcr.io/warppanel/redis:redis-7.4-current","ghcr.io/warppanel/redis:redis-latest","ghcr.io/warppanel/redis:redis-latest-20260829","ghcr.io/warppanel/redis:redis-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "redis-7_2" {
    context = "./build/databases/redis/7.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/redis:redis-7.2-alpine","ghcr.io/warppanel/redis:redis-7.2-alpine-20260829","ghcr.io/warppanel/redis:redis-7.2-alpine-current","ghcr.io/warppanel/redis:redis-7.2","ghcr.io/warppanel/redis:redis-7.2-20260829","ghcr.io/warppanel/redis:redis-7.2-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mongodb-7_0" {
    context = "./build/databases/mongodb/7.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mongodb:mongo-7.0","ghcr.io/warppanel/mongodb:mongo-7.0-20260829","ghcr.io/warppanel/mongodb:mongo-7.0-current","ghcr.io/warppanel/mongodb:mongodb-7.0","ghcr.io/warppanel/mongodb:mongodb-7.0-20260829","ghcr.io/warppanel/mongodb:mongodb-7.0-current","ghcr.io/warppanel/mongodb:mongo-latest","ghcr.io/warppanel/mongodb:mongo-latest-20260829","ghcr.io/warppanel/mongodb:mongo-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mongodb-8_0" {
    context = "./build/databases/mongodb/8.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mongodb:mongo-8.0","ghcr.io/warppanel/mongodb:mongo-8.0-20260829","ghcr.io/warppanel/mongodb:mongo-8.0-current","ghcr.io/warppanel/mongodb:mongodb-8.0","ghcr.io/warppanel/mongodb:mongodb-8.0-20260829","ghcr.io/warppanel/mongodb:mongodb-8.0-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

