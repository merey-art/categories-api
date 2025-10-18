FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libpq-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring zip

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["php-fpm"]
