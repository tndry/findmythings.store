#!/bin/bash
# FindMyThings Deployment Script for Bitnami Azure

echo "🚀 Starting deployment..."

# Pull latest changes
echo "📥 Pulling latest changes from GitHub..."
git pull origin main

# Install/update dependencies
echo "📦 Installing/updating dependencies..."
composer install --no-dev --optimize-autoloader

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (if any)
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Set proper permissions for Bitnami
echo "🔐 Setting proper permissions..."
sudo chown -R bitnami:daemon storage bootstrap/cache public/storage
sudo chmod -R 775 storage bootstrap/cache public/storage

# Clear opcache if available
if command -v php &> /dev/null; then
    echo "🔄 Clearing OPcache..."
    php -r "if (function_exists('opcache_reset')) opcache_reset();"
fi

echo "✅ Deployment completed successfully!"
echo "🌐 Your application should now be updated on the server."
