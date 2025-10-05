FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libsodium-dev

RUN docker-php-ext-install -j$(nproc) \
    pcntl \
    bcmath \
    pdo \
    gd \
    pgsql \
    opcache \
    intl \
    zip \
    soap \
    sockets \
    calendar \
    sodium

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get autoremove -y
