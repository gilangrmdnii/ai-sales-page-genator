#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

echo "▸ Configuring Apache to listen on port ${PORT}..."
sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s|<VirtualHost \*:[0-9]*>|<VirtualHost *:${PORT}>|" /etc/apache2/sites-available/000-default.conf

# If using SQLite, ensure the file exists and is writable by Apache (www-data)
if [ "${DB_CONNECTION}" = "sqlite" ]; then
    DB_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    echo "▸ Preparing SQLite at ${DB_PATH}..."
    mkdir -p "$(dirname "$DB_PATH")"
    touch "$DB_PATH"
    chown -R www-data:www-data "$(dirname "$DB_PATH")"
    chmod 664 "$DB_PATH"
    chmod 775 "$(dirname "$DB_PATH")"
fi

# Storage + cache must be writable too
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "▸ Running migrations..."
php artisan migrate --force

# Seed only if SEED_DEMO=true (toggle from Render env)
if [ "${SEED_DEMO}" = "true" ]; then
    echo "▸ Seeding demo data..."
    php artisan db:seed --class=DemoSeeder --force
fi

echo "▸ Caching config / routes / views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "▸ Starting Apache on port ${PORT}..."
exec apache2-foreground
