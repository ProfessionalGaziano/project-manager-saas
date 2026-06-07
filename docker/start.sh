#!/bin/bash

set -e

echo "Starting Laravel container..."

php artisan optimize:clear || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

php artisan storage:link || true

exec apache2-foreground