#!/bin/sh
set -e

# Wait for database to be ready (optional but helpful)
# sleep 5

# Run Laravel optimization commands
echo "Running optimization commands..."
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations if database is available
echo "Running migrations..."
php artisan migrate --force

# Execute the main command (PHP-FPM)
exec "$@"
