FROM php:8.3-fpm

USER root

COPY containers/php/conf.d /usr/local/etc/php/conf.d

RUN apt-get update && apt-get install -y --fix-missing \
        curl \
        zip \
        unzip \
        git \
        libmemcached-dev \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libssl-dev \
        libwebp-dev \
        libxpm-dev \
        libmagickwand-dev \
        libonig-dev && \
    echo "\e[1;33mInstall important libraries\e[0m" && \
    docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm && \
    docker-php-ext-install pdo pdo_pgsql pgsql gd sockets pcntl && \
    git clone https://github.com/Imagick/imagick && \
    cd imagick && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    docker-php-ext-enable imagick && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN rm -rf /var/www/html/imagick
