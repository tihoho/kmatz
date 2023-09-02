FROM php:7.4-fpm

ENV USER_ID 1000
ENV GROUP_ID 1000

ENV PHP_IDE_CONFIG="serverName=dockerHost"

RUN mkdir -p /var/www/docker/cache/
ENV COMPOSER_HOME=/var/www/docker/cache/composer

# Install PHP extensions dependencies
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxslt1-dev \
    libxml2-dev \
    zlib1g-dev \
    libbz2-dev \
    libzip-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    iconv \
    mbstring \
    mysqli \
    pdo_mysql \
    xsl \
    zip \
    soap \
    bcmath \
    bz2 \
    opcache \
    exif

RUN pecl install xdebug-3.0.4
RUN docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

WORKDIR /var/www

USER "${USER_ID}:${GROUP_ID}"

CMD ["php-fpm"]
