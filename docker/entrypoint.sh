#!/bin/sh
set -e

# Copy .env if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Install composer dependencies if vendor directory is missing
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "Vendor directory missing. Installing composer dependencies..."
    composer install --no-interaction --optimize-autoloader
else
    # Always run dump-autoload to be safe if vendor exists
    composer dump-autoload --optimize
fi

# Generate application key if not set
APP_KEY_VALUE=$(grep "^APP_KEY=" .env | cut -d '=' -f 2)
if [ -z "$APP_KEY_VALUE" ]; then
    echo "Generating application key..."
    php artisan key:generate
fi

# Install npm dependencies and build assets if node_modules is missing
if [ ! -d "node_modules" ]; then
    echo "Node modules missing. Installing and building assets..."
    npm install
    npm run build
fi

# Set permissions for storage and bootstrap/cache
echo "Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Execute the passed command
exec "$@"
