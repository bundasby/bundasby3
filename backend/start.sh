#!/bin/bash
# Railway start script for Laravel

# Run migrations
php artisan migrate --force

# Seed database if empty
php artisan db:seed --force || true

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
