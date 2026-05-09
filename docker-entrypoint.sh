#!/bin/bash
set -e

# Generate .env from environment variables injected by Render
cat > /var/www/html/.env <<EOF
APP_NAME="${APP_NAME}"
APP_ENV="${APP_ENV}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG}"
APP_URL="${APP_URL}"
ASSET_URL="${ASSET_URL}"

LOG_CHANNEL=stderr
LOG_LEVEL=debug

DB_CONNECTION="${DB_CONNECTION}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

SESSION_DRIVER="${SESSION_DRIVER}"
CACHE_DRIVER="${CACHE_DRIVER}"

CLOUDINARY_URL=cloudinary://${CLOUDINARY_API_KEY}:${CLOUDINARY_API_SECRET}@${CLOUDINARY_CLOUD_NAME}
EOF

# Debug - verify Cloudinary URL was written
echo ">>> CLOUDINARY_URL written:"
grep CLOUDINARY_URL /var/www/html/.env

# Clear and cache config
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start Apache
exec apache2-foreground