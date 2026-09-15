#!/usr/bin/env bash
set -uo pipefail

cd /home/site/wwwroot

# Azure's nginx serves /home/site/wwwroot; Laravel's front controller lives in public/.
cp deploy/nginx-laravel.conf /etc/nginx/sites-available/default
nginx -t && (nginx -s reload || service nginx reload)

mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
chmod -R 777 storage bootstrap/cache

# Build-agent caches can reference dev-only providers that are absent in a --no-dev install.
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php bootstrap/cache/config.php
rm -f bootstrap/cache/routes-v7.php bootstrap/cache/events.php

php artisan package:discover --ansi

php artisan migrate --force
php artisan storage:link || true

php artisan config:cache
php artisan route:cache
php artisan view:cache
