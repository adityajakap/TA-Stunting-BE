#!/usr/bin/env bash
set -e

echo "Starting Docker Entrypoint for Backend..."

# 1. Setup .env
if [ ! -f ".env" ]; then
    echo ".env not found, copying from .env.docker..."
    cp .env.docker .env
fi

# 2. Install dependencies
echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. Generate key
echo "Generating APP_KEY if needed..."
php artisan key:generate --no-interaction

# 4. Wait for Database & Migrate
echo "Waiting for database to be ready and running migrations..."
max_retries=30
count=0
until php artisan migrate --force; do
    count=$((count+1))
    if [ $count -gt $max_retries ]; then
        echo "Failed to migrate database after $max_retries attempts. Database might be down."
        break
    fi
    echo "Database not ready yet. Waiting 2 seconds... ($count/$max_retries)"
    sleep 2
done

# 5. Seed Database (ignore error if already seeded)
echo "Running seeders..."
php artisan db:seed --force || echo "Seeders already run or failed."

# 6. Storage Link
echo "Linking storage..."
php artisan storage:link || true

# 7. Cache config
echo "Caching configuration..."
php artisan config:cache

echo "Setup complete! Starting PHP-FPM..."
# Execute the main container command (CMD from Dockerfile)
exec "$@"
