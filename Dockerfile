FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev

# Fix GD include paths for Debian
RUN docker-php-ext-configure gd \
    --with-jpeg=/usr/include \
    --with-freetype=/usr/include/freetype2

# Install all extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    zip \
    mbstring \
    exif \
    gd

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
 && sed -ri -e 's!<Directory /var/www/html>!<Directory /var/www/html/public>!g' /etc/apache2/apache2.conf

COPY . /var/www/html

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
