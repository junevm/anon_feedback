#!/bin/bash

# Fix permissions for Laravel storage and cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Execute the original command
exec php-fpm
