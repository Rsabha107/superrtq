#!/usr/bin/env bash
set -uo pipefail

cd /home/site/wwwroot

# Azure's nginx serves /home/site/wwwroot; Laravel's front controller lives in public/.
cp deploy/nginx-laravel.conf /etc/nginx/sites-available/default
nginx -t && (nginx -s reload || service nginx reload)

mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
chmod -R 777 storage bootstrap/cache

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force
php artisan storage:link || true

php artisan config:cache
php artisan route:cache
php artisan view:cache
