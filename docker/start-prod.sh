#!/usr/bin/env bash
set -euo pipefail
cd /var/www/html

# perms
chown -R www:www storage bootstrap/cache || true
find storage -type d -exec chmod 775 {} \; || true
find storage -type f -exec chmod 664 {} \; || true
chmod -R 775 bootstrap/cache || true

# If you want to ensure optimized autoloads in the built image, you can keep this line commented.
# composer dump-autoload -o --no-dev || true

# Clear then (re)discover packages (this needs artisan present)
php artisan config:clear || true
php artisan event:clear || true
php artisan route:clear || true
php artisan view:clear || true

php artisan package:discover --ansi || true
php artisan optimize || true
php artisan event:cache || true

# Optional (uncomment if you want auto-migrate on container start)
# php artisan migrate --force || true

exec php-fpm -F
