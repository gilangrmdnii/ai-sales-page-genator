# syntax=docker/dockerfile:1.7

# ─────────────────────────────────────────────────────────────────
# Stage 1: build frontend assets with Node
# ─────────────────────────────────────────────────────────────────
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY resources resources
COPY public public
COPY vite.config.js tailwind.config.js postcss.config.js ./
RUN npm run build

# ─────────────────────────────────────────────────────────────────
# Stage 2: production image — PHP + Apache + Composer
# ─────────────────────────────────────────────────────────────────
FROM php:8.3-apache

# System deps + PHP extensions Laravel needs
RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
        libxml2-dev libicu-dev libpq-dev zip unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip intl opcache \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache: serve from /public, enable rewrite
# Force-remove all MPM symlinks then enable only mpm_prefork (only one compatible with mod_php)
RUN rm -f /etc/apache2/mods-enabled/mpm_*.conf /etc/apache2/mods-enabled/mpm_*.load \
    && ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf \
    && ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
    && a2enmod rewrite headers \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Default PORT — entrypoint will sed actual value at runtime
ENV PORT=8080

WORKDIR /var/www/html

# Copy composer files first for cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy app + built frontend
COPY . .
COPY --from=frontend /app/public/build ./public/build

RUN composer dump-autoload --optimize --no-dev \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Entrypoint runs migrations + cache, then starts Apache
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080
CMD ["/usr/local/bin/entrypoint.sh"]
