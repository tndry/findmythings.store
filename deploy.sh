#!/bin/bash
# FindMyThings Deployment Script for Bitnami Azure

echo "Starting deployment..."

# Pull latest changes
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Fix permissions
echo "Fixing permissions..."
sudo chown -R bitnami:bitnami storage/ bootstrap/cache/
sudo chmod -R 775 storage/ bootstrap/cache/

# Clear ALL caches (in correct order)
echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

# Remove cached files
sudo rm -rf storage/framework/views/*
sudo rm -rf bootstrap/cache/*.php

# Check if .env exists and APP_KEY is set
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
    php artisan key:generate
fi

# Set permissions again
sudo chown -R bitnami:bitnami storage/ bootstrap/cache/
sudo chmod -R 775 storage/ bootstrap/cache/

# Discover packages (this should now include InnoShop providers)
echo "Discovering packages..."
php artisan package:discover --ansi

# Try to cache config (might fail if DB not ready)
echo "Caching configuration..."
php artisan config:cache || echo "Config cache failed, continuing..."

# Run migrations
echo "Running migrations..."
php artisan migrate --force || echo "Migration failed, continuing..."

# Final permission fix for web server
sudo chown -R bitnami:daemon storage/ bootstrap/cache/
sudo chmod -R 775 storage/ bootstrap/cache/

echo "Deployment completed!"

# Test basic functionality
echo "Testing application..."
php artisan --version
php artisan route:list | head -3 || echo "Route list check completed"
