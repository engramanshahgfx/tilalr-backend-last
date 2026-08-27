#!/bin/sh

echo "==> Starting Docker Entrypoint..."

# Create .env if it does not exist
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.example ]; then
        echo "--> Copying .env.example to .env..."
        cp /var/www/html/.env.example /var/www/html/.env
    else
        echo "--> Creating blank .env..."
        touch /var/www/html/.env
    fi
fi

# Ensure composer dependencies exist
if [ ! -f /var/www/html/vendor/autoload.php ]; then
    echo "--> Installing composer dependencies..."
    composer install --no-interaction --optimize-autoloader --no-dev
fi

# Ensure required Laravel directories exist with permissions
mkdir -p /var/www/html/storage/app/public \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate APP_KEY if not already set
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "--> Generating application key..."
    php artisan key:generate --force --no-interaction || true
fi

# Link storage
echo "--> Creating storage link..."
php artisan storage:link --force || true

# Clear caches to avoid stale config
echo "--> Clearing application caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true

# Run database migrations if enabled
if [ "$AUTO_MIGRATE" = "true" ]; then
    echo "--> Running database migrations..."
    php artisan migrate --force || true
fi

echo "--> Starting Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
