#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

# If using SQLite, ensure the file exists and is writable
if [ "${DB_CONNECTION}" = "sqlite" ]; then
    DB_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    echo "▸ Preparing SQLite at ${DB_PATH}..."
    mkdir -p "$(dirname "$DB_PATH")"
    touch "$DB_PATH"
    chmod 664 "$DB_PATH"
    chmod 775 "$(dirname "$DB_PATH")"
fi

# Storage + cache must be writable
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

echo "▸ Starting PHP built-in server on 0.0.0.0:${PORT} (4 workers)..."
export PHP_CLI_SERVER_WORKERS=4
exec php -S "0.0.0.0:${PORT}" -t public server.php
