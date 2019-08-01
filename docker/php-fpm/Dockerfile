FROM php:7.2-fpm-alpine

RUN apk add --no-cache \
    sudo \
    bash \
    shadow \
    freetype \
    libpng \
    libjpeg-turbo \
    libxml2 \
    libsodium \
    libpq \
    icu-libs \
    postgresql-client \
    && \
    apk add --update --no-cache --virtual .build $PHPIZE_DEPS \
        freetype-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        libxml2-dev \
        libsodium-dev \
        postgresql-dev \
        icu-dev \
        g++ \
    && \
    docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install -j1 gd && \
    docker-php-ext-install zip && \
    docker-php-ext-install soap && \
    docker-php-ext-install sodium && \
    docker-php-ext-install pdo && \
    docker-php-ext-install pdo_pgsql && \
    docker-php-ext-install intl && \
    docker-php-ext-install calendar && \
    apk del .build \
    && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && \
    sudo -EH -u www-data bash -c "composer global require hirak/prestissimo --no-plugins --no-scripts && composer clearcache"

RUN apk add --no-cache --update libmemcached-libs zlib
RUN set -xe && \
    cd /tmp/ && \
    apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS && \
    apk add --no-cache --update --virtual .memcached-deps zlib-dev libmemcached-dev cyrus-sasl-dev && \
# Install igbinary (memcached's deps)
    pecl install igbinary && \
# Install memcached
    ( \
        pecl install --nobuild memcached && \
        cd "$(pecl config-get temp_dir)/memcached" && \
        phpize && \
        ./configure --enable-memcached-igbinary && \
        make -j$(nproc) && \
        make install && \
        cd /tmp/ \
    ) && \
# Enable PHP extensions
    docker-php-ext-enable igbinary memcached && \
    rm -rf /tmp/* && \
    apk del .memcached-deps .phpize-deps