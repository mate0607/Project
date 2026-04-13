#!/bin/bash
# ─── Autonex Deploy Script ───────────────────────────────
# Futtatás a szerveren: bash deploy.sh
set -e

echo "🚀 Autonex deploy indítása..."

# Karbantartási mód bekapcsolása
php artisan down --refresh=15

# Legújabb kód lehúzása
git pull origin main

# Composer függőségek (production: no-dev)
composer install --no-dev --optimize-autoloader --no-interaction

# Migrációk futtatása
php artisan migrate --force

# Cache újraépítés
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link (ha még nincs)
php artisan storage:link 2>/dev/null || true

# Jogosultságok
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Karbantartási mód kikapcsolása
php artisan up

echo "✅ Deploy kész!"
