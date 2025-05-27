#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "generating application key..."
php artisan key:generate --show

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Publishing cloudinary provider..."
php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tags="cloudinary-laravel-config"

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed

echo "Running passport..."
php artisan passport:client --personal

echo "Running passport..."
php artisan passprt:keys
