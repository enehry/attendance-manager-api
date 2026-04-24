#!/bin/sh
set -e

# Wait for database to be ready
echo "Waiting for database..."
until nc -z db 5432; do
  echo "Database is unavailable - sleeping"
  sleep 1
done
echo "Database is up - continuing"

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
