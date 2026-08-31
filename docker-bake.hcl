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
    tags = ["ghcr.io/gr8man/php:8.0-fpm-alpine","ghcr.io/gr8man/php:8.0-fpm-alpine-20260831","ghcr.io/gr8man/php:8.0-fpm-alpine-current","ghcr.io/gr8man/php:8.0-fpm","ghcr.io/gr8man/php:8.0-fpm-20260831","ghcr.io/gr8man/php:8.0-fpm-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_1" {
    context = "./build/php-fpm/8.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:8.1-fpm-alpine","ghcr.io/gr8man/php:8.1-fpm-alpine-20260831","ghcr.io/gr8man/php:8.1-fpm-alpine-current","ghcr.io/gr8man/php:8.1-fpm","ghcr.io/gr8man/php:8.1-fpm-20260831","ghcr.io/gr8man/php:8.1-fpm-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_2" {
    context = "./build/php-fpm/8.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:8.2-fpm-alpine","ghcr.io/gr8man/php:8.2-fpm-alpine-20260831","ghcr.io/gr8man/php:8.2-fpm-alpine-current","ghcr.io/gr8man/php:8.2-fpm","ghcr.io/gr8man/php:8.2-fpm-20260831","ghcr.io/gr8man/php:8.2-fpm-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_3" {
    context = "./build/php-fpm/8.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:8.3-fpm-alpine","ghcr.io/gr8man/php:8.3-fpm-alpine-20260831","ghcr.io/gr8man/php:8.3-fpm-alpine-current","ghcr.io/gr8man/php:8.3-fpm","ghcr.io/gr8man/php:8.3-fpm-20260831","ghcr.io/gr8man/php:8.3-fpm-current","ghcr.io/gr8man/php:latest-fpm","ghcr.io/gr8man/php:latest-fpm-20260831","ghcr.io/gr8man/php:latest-fpm-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_4" {
    context = "./build/php-fpm/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:8.4-fpm-alpine","ghcr.io/gr8man/php:8.4-fpm-alpine-20260831","ghcr.io/gr8man/php:8.4-fpm-alpine-current","ghcr.io/gr8man/php:8.4-fpm","ghcr.io/gr8man/php:8.4-fpm-20260831","ghcr.io/gr8man/php:8.4-fpm-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_5" {
    context = "./build/php-fpm/8.5"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:8.5-fpm-alpine","ghcr.io/gr8man/php:8.5-fpm-alpine-20260831","ghcr.io/gr8man/php:8.5-fpm-alpine-current","ghcr.io/gr8man/php:8.5-fpm-dev","ghcr.io/gr8man/php:8.5-fpm-dev-20260831","ghcr.io/gr8man/php:8.5-fpm-dev-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-5_6" {
    context = "./build/php-fpm/5.6"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:5.6-fpm-alpine","ghcr.io/gr8man/php:5.6-fpm-alpine-20260831","ghcr.io/gr8man/php:5.6-fpm-alpine-current","ghcr.io/gr8man/php:5.6-fpm","ghcr.io/gr8man/php:5.6-fpm-20260831","ghcr.io/gr8man/php:5.6-fpm-current"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_0" {
    context = "./build/php-fpm/7.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:7.0-fpm-alpine","ghcr.io/gr8man/php:7.0-fpm-alpine-20260831","ghcr.io/gr8man/php:7.0-fpm-alpine-current","ghcr.io/gr8man/php:7.0-fpm","ghcr.io/gr8man/php:7.0-fpm-20260831","ghcr.io/gr8man/php:7.0-fpm-current"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_1" {
    context = "./build/php-fpm/7.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:7.1-fpm-alpine","ghcr.io/gr8man/php:7.1-fpm-alpine-20260831","ghcr.io/gr8man/php:7.1-fpm-alpine-current","ghcr.io/gr8man/php:7.1-fpm","ghcr.io/gr8man/php:7.1-fpm-20260831","ghcr.io/gr8man/php:7.1-fpm-current"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_2" {
    context = "./build/php-fpm/7.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:7.2-fpm-alpine","ghcr.io/gr8man/php:7.2-fpm-alpine-20260831","ghcr.io/gr8man/php:7.2-fpm-alpine-current","ghcr.io/gr8man/php:7.2-fpm","ghcr.io/gr8man/php:7.2-fpm-20260831","ghcr.io/gr8man/php:7.2-fpm-current"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_3" {
    context = "./build/php-fpm/7.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:7.3-fpm-alpine","ghcr.io/gr8man/php:7.3-fpm-alpine-20260831","ghcr.io/gr8man/php:7.3-fpm-alpine-current","ghcr.io/gr8man/php:7.3-fpm","ghcr.io/gr8man/php:7.3-fpm-20260831","ghcr.io/gr8man/php:7.3-fpm-current"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_4" {
    context = "./build/php-fpm/7.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/php:7.4-fpm-alpine","ghcr.io/gr8man/php:7.4-fpm-alpine-20260831","ghcr.io/gr8man/php:7.4-fpm-alpine-current","ghcr.io/gr8man/php:7.4-fpm","ghcr.io/gr8man/php:7.4-fpm-20260831","ghcr.io/gr8man/php:7.4-fpm-current"]
    platforms = ["linux/amd64"]
}

target "frankenphp-8_2" {
    context = "./build/frankenphp/8.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/frankenphp:frankenphp-8.2-alpine","ghcr.io/gr8man/frankenphp:frankenphp-8.2-alpine-20260831","ghcr.io/gr8man/frankenphp:frankenphp-8.2-alpine-current","ghcr.io/gr8man/frankenphp:frankenphp-8.2","ghcr.io/gr8man/frankenphp:frankenphp-8.2-20260831","ghcr.io/gr8man/frankenphp:frankenphp-8.2-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_3" {
    context = "./build/frankenphp/8.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/frankenphp:frankenphp-8.3-alpine","ghcr.io/gr8man/frankenphp:frankenphp-8.3-alpine-20260831","ghcr.io/gr8man/frankenphp:frankenphp-8.3-alpine-current","ghcr.io/gr8man/frankenphp:frankenphp-8.3","ghcr.io/gr8man/frankenphp:frankenphp-8.3-20260831","ghcr.io/gr8man/frankenphp:frankenphp-8.3-current","ghcr.io/gr8man/frankenphp:frankenphp-latest","ghcr.io/gr8man/frankenphp:frankenphp-latest-20260831","ghcr.io/gr8man/frankenphp:frankenphp-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_4" {
    context = "./build/frankenphp/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/frankenphp:frankenphp-8.4-alpine","ghcr.io/gr8man/frankenphp:frankenphp-8.4-alpine-20260831","ghcr.io/gr8man/frankenphp:frankenphp-8.4-alpine-current","ghcr.io/gr8man/frankenphp:frankenphp-8.4","ghcr.io/gr8man/frankenphp:frankenphp-8.4-20260831","ghcr.io/gr8man/frankenphp:frankenphp-8.4-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_5" {
    context = "./build/frankenphp/8.5"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/frankenphp:frankenphp-8.5-alpine","ghcr.io/gr8man/frankenphp:frankenphp-8.5-alpine-20260831","ghcr.io/gr8man/frankenphp:frankenphp-8.5-alpine-current","ghcr.io/gr8man/frankenphp:frankenphp-dev","ghcr.io/gr8man/frankenphp:frankenphp-dev-20260831","ghcr.io/gr8man/frankenphp:frankenphp-dev-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "nginx" {
    context = "./build/nginx"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/nginx:nginx-alpine","ghcr.io/gr8man/nginx:nginx-alpine-20260831","ghcr.io/gr8man/nginx:nginx-alpine-current","ghcr.io/gr8man/nginx:nginx-latest","ghcr.io/gr8man/nginx:nginx-latest-20260831","ghcr.io/gr8man/nginx:nginx-latest-current","ghcr.io/gr8man/nginx:nginx-1.27","ghcr.io/gr8man/nginx:nginx-1.27-20260831","ghcr.io/gr8man/nginx:nginx-1.27-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "apache" {
    context = "./build/apache"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/apache:apache-alpine","ghcr.io/gr8man/apache:apache-alpine-20260831","ghcr.io/gr8man/apache:apache-alpine-current","ghcr.io/gr8man/apache:apache-latest","ghcr.io/gr8man/apache:apache-latest-20260831","ghcr.io/gr8man/apache:apache-latest-current","ghcr.io/gr8man/apache:httpd-2.4","ghcr.io/gr8man/apache:httpd-2.4-20260831","ghcr.io/gr8man/apache:httpd-2.4-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "openlitespeed" {
    context = "./build/openlitespeed"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/openlitespeed:openlitespeed-alpine","ghcr.io/gr8man/openlitespeed:openlitespeed-alpine-20260831","ghcr.io/gr8man/openlitespeed:openlitespeed-alpine-current","ghcr.io/gr8man/openlitespeed:openlitespeed-latest","ghcr.io/gr8man/openlitespeed:openlitespeed-latest-20260831","ghcr.io/gr8man/openlitespeed:openlitespeed-latest-current","ghcr.io/gr8man/openlitespeed:ols-latest","ghcr.io/gr8man/openlitespeed:ols-latest-20260831","ghcr.io/gr8man/openlitespeed:ols-latest-current"]
    platforms = ["linux/amd64"]
}

target "caddy" {
    context = "./build/caddy"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/caddy:caddy-alpine","ghcr.io/gr8man/caddy:caddy-alpine-20260831","ghcr.io/gr8man/caddy:caddy-alpine-current","ghcr.io/gr8man/caddy:caddy-latest","ghcr.io/gr8man/caddy:caddy-latest-20260831","ghcr.io/gr8man/caddy:caddy-latest-current","ghcr.io/gr8man/caddy:caddy-2.8","ghcr.io/gr8man/caddy:caddy-2.8-20260831","ghcr.io/gr8man/caddy:caddy-2.8-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "lighttpd" {
    context = "./build/lighttpd"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/lighttpd:lighttpd-alpine","ghcr.io/gr8man/lighttpd:lighttpd-alpine-20260831","ghcr.io/gr8man/lighttpd:lighttpd-alpine-current","ghcr.io/gr8man/lighttpd:lighttpd-latest","ghcr.io/gr8man/lighttpd:lighttpd-latest-20260831","ghcr.io/gr8man/lighttpd:lighttpd-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v2_11" {
    context = "./build/traefik/2.11"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/traefik:traefik-2.11","ghcr.io/gr8man/traefik:traefik-2.11-20260831","ghcr.io/gr8man/traefik:traefik-2.11-current","ghcr.io/gr8man/traefik:traefik-v2.11","ghcr.io/gr8man/traefik:traefik-v2.11-20260831","ghcr.io/gr8man/traefik:traefik-v2.11-current","ghcr.io/gr8man/traefik:traefik-v2","ghcr.io/gr8man/traefik:traefik-v2-20260831","ghcr.io/gr8man/traefik:traefik-v2-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v3_1" {
    context = "./build/traefik/3.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/traefik:traefik-3.1","ghcr.io/gr8man/traefik:traefik-3.1-20260831","ghcr.io/gr8man/traefik:traefik-3.1-current","ghcr.io/gr8man/traefik:traefik-v3.1","ghcr.io/gr8man/traefik:traefik-v3.1-20260831","ghcr.io/gr8man/traefik:traefik-v3.1-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v3_2" {
    context = "./build/traefik/3.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/traefik:traefik-3.2","ghcr.io/gr8man/traefik:traefik-3.2-20260831","ghcr.io/gr8man/traefik:traefik-3.2-current","ghcr.io/gr8man/traefik:traefik-v3.2","ghcr.io/gr8man/traefik:traefik-v3.2-20260831","ghcr.io/gr8man/traefik:traefik-v3.2-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v3_3" {
    context = "./build/traefik/3.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/traefik:traefik-3.3","ghcr.io/gr8man/traefik:traefik-3.3-20260831","ghcr.io/gr8man/traefik:traefik-3.3-current","ghcr.io/gr8man/traefik:traefik-v3.3","ghcr.io/gr8man/traefik:traefik-v3.3-20260831","ghcr.io/gr8man/traefik:traefik-v3.3-current","ghcr.io/gr8man/traefik:traefik-v3","ghcr.io/gr8man/traefik:traefik-v3-20260831","ghcr.io/gr8man/traefik:traefik-v3-current","ghcr.io/gr8man/traefik:traefik-latest","ghcr.io/gr8man/traefik:traefik-latest-20260831","ghcr.io/gr8man/traefik:traefik-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mysql-8_4" {
    context = "./build/databases/mysql/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/mysql:mysql-8.4","ghcr.io/gr8man/mysql:mysql-8.4-20260831","ghcr.io/gr8man/mysql:mysql-8.4-current","ghcr.io/gr8man/mysql:mysql-lts","ghcr.io/gr8man/mysql:mysql-lts-20260831","ghcr.io/gr8man/mysql:mysql-lts-current","ghcr.io/gr8man/mysql:mysql-latest","ghcr.io/gr8man/mysql:mysql-latest-20260831","ghcr.io/gr8man/mysql:mysql-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mysql-8_0" {
    context = "./build/databases/mysql/8.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/mysql:mysql-8.0","ghcr.io/gr8man/mysql:mysql-8.0-20260831","ghcr.io/gr8man/mysql:mysql-8.0-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mariadb-11_4" {
    context = "./build/databases/mariadb/11.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/mariadb:mariadb-11.4","ghcr.io/gr8man/mariadb:mariadb-11.4-20260831","ghcr.io/gr8man/mariadb:mariadb-11.4-current","ghcr.io/gr8man/mariadb:mariadb-lts","ghcr.io/gr8man/mariadb:mariadb-lts-20260831","ghcr.io/gr8man/mariadb:mariadb-lts-current","ghcr.io/gr8man/mariadb:mariadb-latest","ghcr.io/gr8man/mariadb:mariadb-latest-20260831","ghcr.io/gr8man/mariadb:mariadb-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mariadb-10_11" {
    context = "./build/databases/mariadb/10.11"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/mariadb:mariadb-10.11","ghcr.io/gr8man/mariadb:mariadb-10.11-20260831","ghcr.io/gr8man/mariadb:mariadb-10.11-current","ghcr.io/gr8man/mariadb:mariadb-10.11-lts","ghcr.io/gr8man/mariadb:mariadb-10.11-lts-20260831","ghcr.io/gr8man/mariadb:mariadb-10.11-lts-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "postgres-17" {
    context = "./build/databases/postgres/17"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/postgres:postgres-17-alpine","ghcr.io/gr8man/postgres:postgres-17-alpine-20260831","ghcr.io/gr8man/postgres:postgres-17-alpine-current","ghcr.io/gr8man/postgres:postgres-17","ghcr.io/gr8man/postgres:postgres-17-20260831","ghcr.io/gr8man/postgres:postgres-17-current","ghcr.io/gr8man/postgres:postgres-latest","ghcr.io/gr8man/postgres:postgres-latest-20260831","ghcr.io/gr8man/postgres:postgres-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "postgres-16" {
    context = "./build/databases/postgres/16"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/postgres:postgres-16-alpine","ghcr.io/gr8man/postgres:postgres-16-alpine-20260831","ghcr.io/gr8man/postgres:postgres-16-alpine-current","ghcr.io/gr8man/postgres:postgres-16","ghcr.io/gr8man/postgres:postgres-16-20260831","ghcr.io/gr8man/postgres:postgres-16-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "redis-7_4" {
    context = "./build/databases/redis/7.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/redis:redis-7.4-alpine","ghcr.io/gr8man/redis:redis-7.4-alpine-20260831","ghcr.io/gr8man/redis:redis-7.4-alpine-current","ghcr.io/gr8man/redis:redis-7.4","ghcr.io/gr8man/redis:redis-7.4-20260831","ghcr.io/gr8man/redis:redis-7.4-current","ghcr.io/gr8man/redis:redis-latest","ghcr.io/gr8man/redis:redis-latest-20260831","ghcr.io/gr8man/redis:redis-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "redis-7_2" {
    context = "./build/databases/redis/7.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/redis:redis-7.2-alpine","ghcr.io/gr8man/redis:redis-7.2-alpine-20260831","ghcr.io/gr8man/redis:redis-7.2-alpine-current","ghcr.io/gr8man/redis:redis-7.2","ghcr.io/gr8man/redis:redis-7.2-20260831","ghcr.io/gr8man/redis:redis-7.2-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mongodb-7_0" {
    context = "./build/databases/mongodb/7.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/mongodb:mongo-7.0","ghcr.io/gr8man/mongodb:mongo-7.0-20260831","ghcr.io/gr8man/mongodb:mongo-7.0-current","ghcr.io/gr8man/mongodb:mongodb-7.0","ghcr.io/gr8man/mongodb:mongodb-7.0-20260831","ghcr.io/gr8man/mongodb:mongodb-7.0-current","ghcr.io/gr8man/mongodb:mongo-latest","ghcr.io/gr8man/mongodb:mongo-latest-20260831","ghcr.io/gr8man/mongodb:mongo-latest-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mongodb-8_0" {
    context = "./build/databases/mongodb/8.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/gr8man/mongodb:mongo-8.0","ghcr.io/gr8man/mongodb:mongo-8.0-20260831","ghcr.io/gr8man/mongodb:mongo-8.0-current","ghcr.io/gr8man/mongodb:mongodb-8.0","ghcr.io/gr8man/mongodb:mongodb-8.0-20260831","ghcr.io/gr8man/mongodb:mongodb-8.0-current"]
    platforms = ["linux/amd64","linux/arm64"]
}

