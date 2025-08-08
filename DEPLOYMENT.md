# FindMyThings - Deployment Guide

## Local Development Setup

1. Clone repository
2. Copy `.env.example` to `.env`
3. Configure your local database and email settings
4. Run: `composer install`
5. Run: `php artisan key:generate`
6. Run: `php artisan migrate`

## Production Deployment (findmythings.store)

### Google OAuth IPB Login System Update

**Important**: This update adds Google OAuth login specifically for IPB students/staff (@apps.ipb.ac.id)

### Pre-deployment Checklist
- [x] Code tested locally
- [x] Google OAuth credentials ready
- [x] Server backup plan ready

### Deployment Steps

1. **Connect to server:**
   ```bash
   ssh tandry@findmythings.store
   cd /path/to/your/laravel/app
   ```

2. **Backup current state:**
   ```bash
   cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
   ```

3. **Pull latest changes:**
   ```bash
   git pull origin main
   ```

4. **Install dependencies:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

5. **Update .env file for Google OAuth:**
   ```bash
   # Add these lines to your .env file:
   GOOGLE_CLIENT_ID=YOUR_GOOGLE_CLIENT_ID_HERE
   GOOGLE_CLIENT_SECRET=YOUR_GOOGLE_CLIENT_SECRET_HERE
   GOOGLE_REDIRECT_URI=https://findmythings.store/google-ipb/callback
   
   # Ensure APP_URL is correct:
   APP_URL=https://findmythings.store
   ```
   
   **Note**: Replace the placeholder values with your actual Google OAuth credentials

6. **Clear and optimize caches:**
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

7. **Test deployment:**
   ```bash
   php artisan route:list --name=google.ipb
   ```

### Google Cloud Console Update
Update redirect URI to: `https://findmythings.store/google-ipb/callback`

### First Time Setup

1. Clone the repository to your server
2. Copy `.env.example` to `.env`
3. Configure production settings in `.env`:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   DEBUGBAR_ENABLED=false
   
   # Configure your production database
   DB_HOST=your_db_host
   DB_DATABASE=your_db_name
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password
   
   # Configure your production email
   MAIL_USERNAME=your_email@gmail.com
   MAIL_PASSWORD=your_app_password
   MAIL_FROM_ADDRESS=your_email@gmail.com
   ```
4. Generate application key: `php artisan key:generate`
5. Run initial deployment: `chmod +x deploy.sh && ./deploy.sh`

### Regular Updates

```bash
./deploy.sh
```

## Important Notes

- Never commit `.env` file to repository
- Use `.env.example` as template for production
- Always test changes locally before deploying
- The `deploy.sh` script handles cache clearing and optimization

## Features

- WhatsApp integration for seller contact
- Image gallery with lightbox
- Submission approval system
- Multi-language support

## Security

- Production environment disables debug mode
- Sensitive data is kept in environment variables
- File permissions are properly set during deployment
