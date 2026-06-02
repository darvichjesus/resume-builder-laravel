#!/bin/bash
set -e

APP_DIR="/var/www/html"
DATA_DIR="/var/data"

echo "==> Starting pdf-maker..."

# ── Persistent storage (Render Disk mounted at /var/data) ──────────────────
if [ -d "$DATA_DIR" ]; then
    echo "==> Linking persistent storage..."
    mkdir -p "$DATA_DIR/storage/app/public"
    mkdir -p "$DATA_DIR/storage/logs"

    # Replace ephemeral storage/app with persistent volume
    rm -rf "$APP_DIR/storage/app"
    ln -sfn "$DATA_DIR/storage/app" "$APP_DIR/storage/app"

    # Symlink public/storage → storage/app/public
    rm -f "$APP_DIR/public/storage"
    ln -sfn "$APP_DIR/storage/app/public" "$APP_DIR/public/storage"

    chown -R www-data:www-data "$DATA_DIR/storage"
fi

cd "$APP_DIR"

# Ensure views directory exists to avoid "View path not found" error during artisan commands
mkdir -p resources/views

# ── Environment ────────────────────────────────────────────────────────────
echo "==> Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── Database ───────────────────────────────────────────────────────────────
echo "==> Running migrations..."
php artisan migrate --force

# ── Start services ─────────────────────────────────────────────────────────
echo "==> Starting PHP-FPM..."
php-fpm -D

echo "==> Starting nginx..."
exec nginx -g 'daemon off;'
