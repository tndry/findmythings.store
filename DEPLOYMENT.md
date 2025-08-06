# FindMyThings - Deployment Guide

## Local Development Setup

1. Clone repository
2. Copy `.env.example` to `.env`
3. Configure your local database and email settings
4. Run: `composer install`
5. Run: `php artisan key:generate`
6. Run: `php artisan migrate`

## Production Deployment (Bitnami Azure)

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
