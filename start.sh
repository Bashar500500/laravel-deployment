# #!/usr/bin/env bash
# echo "Running composer"
# composer global require hirak/prestissimo
# composer install --no-dev --working-dir=/var/www/html

# echo "generating application key..."
# php artisan key:generate --show

# echo "Caching config..."
# php artisan config:cache

# echo "Caching routes..."
# php artisan route:cache

# # echo "Running migrations..."
# # php artisan migrate --force

# # echo "Running seeders..."
# # php artisan db:seed

# # echo "Running passport..."
# # php artisan passport:client --personal

# # echo "Running passport..."
# # php artisan passport:keys

# # echo "Publishing cloudinary provider..."
# # php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tags="cloudinary-laravel-config"



# old
# --------------------------
# new



#!/usr/bin/env bash
set -e

echo "=== Starting Laravel app ==="

# Ensure APP_KEY is set in Render dashboard env vars – do NOT regenerate here
if [ -z "$APP_KEY" ]; then
  echo "⚠️  APP_KEY is not set! Please configure it in Render environment variables."
  exit 1
fi

# Refresh caches
php artisan optimize --no-ansi --force

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

# Start php-fpm (Nginx will proxy requests to it)
php-fpm
