FROM php:8.4-fpm-alpine AS php

RUN apk add --no-cache autoconf build-base \
    && yes '' | pecl install redis \
    && docker-php-ext-enable redis

RUN docker-php-ext-install pdo_mysql

RUN install -o www-data -g www-data -d /var/www/upload/image/

RUN echo -e "post_max_size = 5M\nupload_max_filesize = 5M" >> ${PHP_INI_DIR}/php.ini

RUN apk add autoconf g++ make

RUN pecl install apcu && docker-php-ext-enable apcu

COPY ./php.ini ${PHP_INI_DIR}/php.ini

# ディレクトリの権限を変更
RUN chown -R www-data:www-data /var/www/upload/image && chmod -R 755 /var/www/upload/image


