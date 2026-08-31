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
    tags = ["ghcr.io/warppanel/php:8.0-fpm-alpine-current","ghcr.io/warppanel/php:8.0-fpm-alpine-20260831","ghcr.io/warppanel/php:8.0-fpm-current","ghcr.io/warppanel/php:8.0-fpm-20260831","ghcr.io/warppanel/php:8.0-current","ghcr.io/warppanel/php:8.0-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_1" {
    context = "./build/php-fpm/8.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.1-fpm-alpine-current","ghcr.io/warppanel/php:8.1-fpm-alpine-20260831","ghcr.io/warppanel/php:8.1-fpm-current","ghcr.io/warppanel/php:8.1-fpm-20260831","ghcr.io/warppanel/php:8.1-current","ghcr.io/warppanel/php:8.1-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_2" {
    context = "./build/php-fpm/8.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.2-fpm-alpine-current","ghcr.io/warppanel/php:8.2-fpm-alpine-20260831","ghcr.io/warppanel/php:8.2-fpm-current","ghcr.io/warppanel/php:8.2-fpm-20260831","ghcr.io/warppanel/php:8.2-current","ghcr.io/warppanel/php:8.2-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_3" {
    context = "./build/php-fpm/8.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.3-fpm-alpine-current","ghcr.io/warppanel/php:8.3-fpm-alpine-20260831","ghcr.io/warppanel/php:8.3-fpm-current","ghcr.io/warppanel/php:8.3-fpm-20260831","ghcr.io/warppanel/php:8.3-current","ghcr.io/warppanel/php:8.3-20260831","ghcr.io/warppanel/php:latest-fpm-current","ghcr.io/warppanel/php:latest-fpm-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_4" {
    context = "./build/php-fpm/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.4-fpm-alpine-current","ghcr.io/warppanel/php:8.4-fpm-alpine-20260831","ghcr.io/warppanel/php:8.4-fpm-current","ghcr.io/warppanel/php:8.4-fpm-20260831","ghcr.io/warppanel/php:8.4-current","ghcr.io/warppanel/php:8.4-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_5" {
    context = "./build/php-fpm/8.5"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.5-fpm-alpine-current","ghcr.io/warppanel/php:8.5-fpm-alpine-20260831","ghcr.io/warppanel/php:8.5-fpm-dev-current","ghcr.io/warppanel/php:8.5-fpm-dev-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-5_6" {
    context = "./build/php-fpm/5.6"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:5.6-fpm-alpine-current","ghcr.io/warppanel/php:5.6-fpm-alpine-20260831","ghcr.io/warppanel/php:5.6-fpm-current","ghcr.io/warppanel/php:5.6-fpm-20260831","ghcr.io/warppanel/php:5.6-current","ghcr.io/warppanel/php:5.6-20260831"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_0" {
    context = "./build/php-fpm/7.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.0-fpm-alpine-current","ghcr.io/warppanel/php:7.0-fpm-alpine-20260831","ghcr.io/warppanel/php:7.0-fpm-current","ghcr.io/warppanel/php:7.0-fpm-20260831","ghcr.io/warppanel/php:7.0-current","ghcr.io/warppanel/php:7.0-20260831"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_1" {
    context = "./build/php-fpm/7.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.1-fpm-alpine-current","ghcr.io/warppanel/php:7.1-fpm-alpine-20260831","ghcr.io/warppanel/php:7.1-fpm-current","ghcr.io/warppanel/php:7.1-fpm-20260831","ghcr.io/warppanel/php:7.1-current","ghcr.io/warppanel/php:7.1-20260831"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_2" {
    context = "./build/php-fpm/7.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.2-fpm-alpine-current","ghcr.io/warppanel/php:7.2-fpm-alpine-20260831","ghcr.io/warppanel/php:7.2-fpm-current","ghcr.io/warppanel/php:7.2-fpm-20260831","ghcr.io/warppanel/php:7.2-current","ghcr.io/warppanel/php:7.2-20260831"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_3" {
    context = "./build/php-fpm/7.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.3-fpm-alpine-current","ghcr.io/warppanel/php:7.3-fpm-alpine-20260831","ghcr.io/warppanel/php:7.3-fpm-current","ghcr.io/warppanel/php:7.3-fpm-20260831","ghcr.io/warppanel/php:7.3-current","ghcr.io/warppanel/php:7.3-20260831"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_4" {
    context = "./build/php-fpm/7.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.4-fpm-alpine-current","ghcr.io/warppanel/php:7.4-fpm-alpine-20260831","ghcr.io/warppanel/php:7.4-fpm-current","ghcr.io/warppanel/php:7.4-fpm-20260831","ghcr.io/warppanel/php:7.4-current","ghcr.io/warppanel/php:7.4-20260831"]
    platforms = ["linux/amd64"]
}

target "frankenphp-8_2" {
    context = "./build/frankenphp/8.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:8.2-alpine-current","ghcr.io/warppanel/frankenphp:8.2-alpine-20260831","ghcr.io/warppanel/frankenphp:8.2-current","ghcr.io/warppanel/frankenphp:8.2-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_3" {
    context = "./build/frankenphp/8.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:8.3-alpine-current","ghcr.io/warppanel/frankenphp:8.3-alpine-20260831","ghcr.io/warppanel/frankenphp:8.3-current","ghcr.io/warppanel/frankenphp:8.3-20260831","ghcr.io/warppanel/frankenphp:latest-current","ghcr.io/warppanel/frankenphp:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_4" {
    context = "./build/frankenphp/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:8.4-alpine-current","ghcr.io/warppanel/frankenphp:8.4-alpine-20260831","ghcr.io/warppanel/frankenphp:8.4-current","ghcr.io/warppanel/frankenphp:8.4-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_5" {
    context = "./build/frankenphp/8.5"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:8.5-alpine-current","ghcr.io/warppanel/frankenphp:8.5-alpine-20260831","ghcr.io/warppanel/frankenphp:8.5-current","ghcr.io/warppanel/frankenphp:8.5-20260831","ghcr.io/warppanel/frankenphp:dev-current","ghcr.io/warppanel/frankenphp:dev-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "nginx" {
    context = "./build/nginx"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/nginx:1.27-alpine-current","ghcr.io/warppanel/nginx:1.27-alpine-20260831","ghcr.io/warppanel/nginx:1.27-current","ghcr.io/warppanel/nginx:1.27-20260831","ghcr.io/warppanel/nginx:alpine-current","ghcr.io/warppanel/nginx:alpine-20260831","ghcr.io/warppanel/nginx:latest-current","ghcr.io/warppanel/nginx:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "apache" {
    context = "./build/apache"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/apache:2.4-alpine-current","ghcr.io/warppanel/apache:2.4-alpine-20260831","ghcr.io/warppanel/apache:2.4-current","ghcr.io/warppanel/apache:2.4-20260831","ghcr.io/warppanel/apache:alpine-current","ghcr.io/warppanel/apache:alpine-20260831","ghcr.io/warppanel/apache:latest-current","ghcr.io/warppanel/apache:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "openlitespeed" {
    context = "./build/openlitespeed"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/openlitespeed:1.8-alpine-current","ghcr.io/warppanel/openlitespeed:1.8-alpine-20260831","ghcr.io/warppanel/openlitespeed:1.8-current","ghcr.io/warppanel/openlitespeed:1.8-20260831","ghcr.io/warppanel/openlitespeed:latest-current","ghcr.io/warppanel/openlitespeed:latest-20260831"]
    platforms = ["linux/amd64"]
}

target "caddy" {
    context = "./build/caddy"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/caddy:2.8-alpine-current","ghcr.io/warppanel/caddy:2.8-alpine-20260831","ghcr.io/warppanel/caddy:2.8-current","ghcr.io/warppanel/caddy:2.8-20260831","ghcr.io/warppanel/caddy:alpine-current","ghcr.io/warppanel/caddy:alpine-20260831","ghcr.io/warppanel/caddy:latest-current","ghcr.io/warppanel/caddy:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "lighttpd" {
    context = "./build/lighttpd"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/lighttpd:1.4-alpine-current","ghcr.io/warppanel/lighttpd:1.4-alpine-20260831","ghcr.io/warppanel/lighttpd:1.4-current","ghcr.io/warppanel/lighttpd:1.4-20260831","ghcr.io/warppanel/lighttpd:latest-current","ghcr.io/warppanel/lighttpd:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v2_11" {
    context = "./build/traefik/2.11"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/traefik:2.11-current","ghcr.io/warppanel/traefik:2.11-20260831","ghcr.io/warppanel/traefik:v2.11-current","ghcr.io/warppanel/traefik:v2.11-20260831","ghcr.io/warppanel/traefik:v2-current","ghcr.io/warppanel/traefik:v2-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v3_1" {
    context = "./build/traefik/3.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/traefik:3.1-current","ghcr.io/warppanel/traefik:3.1-20260831","ghcr.io/warppanel/traefik:v3.1-current","ghcr.io/warppanel/traefik:v3.1-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v3_2" {
    context = "./build/traefik/3.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/traefik:3.2-current","ghcr.io/warppanel/traefik:3.2-20260831","ghcr.io/warppanel/traefik:v3.2-current","ghcr.io/warppanel/traefik:v3.2-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "traefik-v3_3" {
    context = "./build/traefik/3.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/traefik:3.3-current","ghcr.io/warppanel/traefik:3.3-20260831","ghcr.io/warppanel/traefik:v3.3-current","ghcr.io/warppanel/traefik:v3.3-20260831","ghcr.io/warppanel/traefik:v3-current","ghcr.io/warppanel/traefik:v3-20260831","ghcr.io/warppanel/traefik:latest-current","ghcr.io/warppanel/traefik:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mysql-8_4" {
    context = "./build/databases/mysql/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mysql:8.4-current","ghcr.io/warppanel/mysql:8.4-20260831","ghcr.io/warppanel/mysql:lts-current","ghcr.io/warppanel/mysql:lts-20260831","ghcr.io/warppanel/mysql:latest-current","ghcr.io/warppanel/mysql:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mysql-8_0" {
    context = "./build/databases/mysql/8.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mysql:8.0-current","ghcr.io/warppanel/mysql:8.0-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mariadb-11_4" {
    context = "./build/databases/mariadb/11.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mariadb:11.4-current","ghcr.io/warppanel/mariadb:11.4-20260831","ghcr.io/warppanel/mariadb:lts-current","ghcr.io/warppanel/mariadb:lts-20260831","ghcr.io/warppanel/mariadb:latest-current","ghcr.io/warppanel/mariadb:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mariadb-10_11" {
    context = "./build/databases/mariadb/10.11"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mariadb:10.11-current","ghcr.io/warppanel/mariadb:10.11-20260831","ghcr.io/warppanel/mariadb:10.11-lts-current","ghcr.io/warppanel/mariadb:10.11-lts-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "postgres-17" {
    context = "./build/databases/postgres/17"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/postgres:17-alpine-current","ghcr.io/warppanel/postgres:17-alpine-20260831","ghcr.io/warppanel/postgres:17-current","ghcr.io/warppanel/postgres:17-20260831","ghcr.io/warppanel/postgres:latest-current","ghcr.io/warppanel/postgres:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "postgres-16" {
    context = "./build/databases/postgres/16"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/postgres:16-alpine-current","ghcr.io/warppanel/postgres:16-alpine-20260831","ghcr.io/warppanel/postgres:16-current","ghcr.io/warppanel/postgres:16-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "redis-7_4" {
    context = "./build/databases/redis/7.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/redis:7.4-alpine-current","ghcr.io/warppanel/redis:7.4-alpine-20260831","ghcr.io/warppanel/redis:7.4-current","ghcr.io/warppanel/redis:7.4-20260831","ghcr.io/warppanel/redis:latest-current","ghcr.io/warppanel/redis:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "redis-7_2" {
    context = "./build/databases/redis/7.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/redis:7.2-alpine-current","ghcr.io/warppanel/redis:7.2-alpine-20260831","ghcr.io/warppanel/redis:7.2-current","ghcr.io/warppanel/redis:7.2-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mongodb-7_0" {
    context = "./build/databases/mongodb/7.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mongodb:7.0-current","ghcr.io/warppanel/mongodb:7.0-20260831","ghcr.io/warppanel/mongodb:latest-current","ghcr.io/warppanel/mongodb:latest-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "mongodb-8_0" {
    context = "./build/databases/mongodb/8.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/mongodb:8.0-current","ghcr.io/warppanel/mongodb:8.0-20260831"]
    platforms = ["linux/amd64","linux/arm64"]
}

