#!/bin/sh
set -e

echo "Deploying application ..."

# Go To Folder
cd /var/www/portfolio

# Update codebase
git pull origin master

# Install dependencies based on lock file
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Migrate database
php artisan migrate --force

# Clear cache
php artisan optimize:clear
php artisan cache:clear
php artisan optimize

# Reload PHP to update opcache
echo "" | sudo -S service php8.0-fpm reload

# Restart Horizon
cd /etc/supervisor/ && supervisorctl restart horizon

echo "Application deployed!"