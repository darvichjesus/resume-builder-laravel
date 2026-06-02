# ---- Build Stage ----
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- PHP Stage ----
FROM php:8.3-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    nginx \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    libxml2-dev \
    postgresql-dev \
    postgresql-client \
    bash

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        mbstring \
        exif \
        pcntl \
        bcmath \
        intl \
        opcache \
        xml

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy application
COPY . .

# Copy built assets from node stage
COPY --from=node_builder /app/public/build ./public/build

# Create required directories before artisan runs
RUN mkdir -p bootstrap/cache \
    storage/logs \
    storage/framework/{cache,sessions,views} \
    && chmod -R 777 bootstrap/cache storage

# Run post-install scripts
RUN composer run-script post-autoload-dump

# Storage & permissions
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Startup script
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
