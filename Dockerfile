# Stage 1 - Build Frontend (Vite)
FROM node:18 AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2 - Backend (Laravel + PHP + Composer)
FROM php:8.2-apache AS backend

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip gd exif

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
 && sed -ri -e 's!<Directory /var/www/html>!<Directory /var/www/html/public>!g' /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app files
COPY . .

# Copy built frontend from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
