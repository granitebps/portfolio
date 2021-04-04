#!/bin/sh
set -e

echo "Deploying application ..."

# Update codebase
git fetch origin master
git reset --hard origin/master

# Install dependencies based on lock file
composer install --no-interaction --prefer-dist --optimize-autoloader

# Migrate database
php artisan migrate --force

# Note: If you're using queue workers, this is the place to restart them.
# ...

# Run test
php artisan test

# Clear cache
php artisan optimize
php artisan cache:clear

# Reload PHP to update opcache
echo "" | sudo -S service php7.4-fpm reload

echo "Application deployed!"