group "default" {
    targets = ["php-fpm-8_0","php-fpm-8_1","php-fpm-8_2","php-fpm-8_3","php-fpm-8_4","php-fpm-8_5","php-fpm-5_6","php-fpm-7_0","php-fpm-7_1","php-fpm-7_2","php-fpm-7_3","php-fpm-7_4","frankenphp-8_2","frankenphp-8_3","frankenphp-8_4","frankenphp-8_5","nginx","apache","openlitespeed"]
}

group "php" {
    targets = ["php-fpm-8_0","php-fpm-8_1","php-fpm-8_2","php-fpm-8_3","php-fpm-8_4","php-fpm-8_5","php-fpm-5_6","php-fpm-7_0","php-fpm-7_1","php-fpm-7_2","php-fpm-7_3","php-fpm-7_4"]
}

group "frankenphp" {
    targets = ["frankenphp-8_2","frankenphp-8_3","frankenphp-8_4","frankenphp-8_5"]
}

group "webservers" {
    targets = ["nginx", "apache", "openlitespeed"]
}

target "php-fpm-8_0" {
    context = "./build/php-fpm/8.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.0-fpm-alpine","ghcr.io/warppanel/php:8.0-fpm"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_1" {
    context = "./build/php-fpm/8.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.1-fpm-alpine","ghcr.io/warppanel/php:8.1-fpm"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_2" {
    context = "./build/php-fpm/8.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.2-fpm-alpine","ghcr.io/warppanel/php:8.2-fpm"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_3" {
    context = "./build/php-fpm/8.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.3-fpm-alpine","ghcr.io/warppanel/php:8.3-fpm","ghcr.io/warppanel/php:latest-fpm"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_4" {
    context = "./build/php-fpm/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.4-fpm-alpine","ghcr.io/warppanel/php:8.4-fpm"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-8_5" {
    context = "./build/php-fpm/8.5"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:8.5-fpm-alpine","ghcr.io/warppanel/php:8.5-fpm-dev"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "php-fpm-5_6" {
    context = "./build/php-fpm/5.6"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:5.6-fpm-alpine","ghcr.io/warppanel/php:5.6-fpm"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_0" {
    context = "./build/php-fpm/7.0"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.0-fpm-alpine","ghcr.io/warppanel/php:7.0-fpm"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_1" {
    context = "./build/php-fpm/7.1"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.1-fpm-alpine","ghcr.io/warppanel/php:7.1-fpm"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_2" {
    context = "./build/php-fpm/7.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.2-fpm-alpine","ghcr.io/warppanel/php:7.2-fpm"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_3" {
    context = "./build/php-fpm/7.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.3-fpm-alpine","ghcr.io/warppanel/php:7.3-fpm"]
    platforms = ["linux/amd64"]
}

target "php-fpm-7_4" {
    context = "./build/php-fpm/7.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/php:7.4-fpm-alpine","ghcr.io/warppanel/php:7.4-fpm"]
    platforms = ["linux/amd64"]
}

target "frankenphp-8_2" {
    context = "./build/frankenphp/8.2"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:frankenphp-8.2-alpine","ghcr.io/warppanel/frankenphp:frankenphp-8.2"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_3" {
    context = "./build/frankenphp/8.3"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:frankenphp-8.3-alpine","ghcr.io/warppanel/frankenphp:frankenphp-8.3","ghcr.io/warppanel/frankenphp:frankenphp-latest"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_4" {
    context = "./build/frankenphp/8.4"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:frankenphp-8.4-alpine","ghcr.io/warppanel/frankenphp:frankenphp-8.4"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "frankenphp-8_5" {
    context = "./build/frankenphp/8.5"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/frankenphp:frankenphp-8.5-alpine","ghcr.io/warppanel/frankenphp:frankenphp-dev"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "nginx" {
    context = "./build/nginx"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/nginx:nginx-alpine","ghcr.io/warppanel/nginx:nginx-latest","ghcr.io/warppanel/nginx:nginx-1.27"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "apache" {
    context = "./build/apache"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/apache:apache-alpine","ghcr.io/warppanel/apache:apache-latest","ghcr.io/warppanel/apache:httpd-2.4"]
    platforms = ["linux/amd64","linux/arm64"]
}

target "openlitespeed" {
    context = "./build/openlitespeed"
    dockerfile = "Dockerfile"
    tags = ["ghcr.io/warppanel/openlitespeed:openlitespeed-alpine","ghcr.io/warppanel/openlitespeed:openlitespeed-latest","ghcr.io/warppanel/openlitespeed:ols-latest"]
    platforms = ["linux/amd64"]
}

