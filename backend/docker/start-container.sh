#!/bin/bash
set -e

# Fix permissions for mounted volumes
if [ ! -z "$WWWUSER" ]; then
    usermod -u $WWWUSER sail 2>/dev/null || true
fi

# Ensure storage and cache directories have correct permissions
chown -R sail:sail /var/www/html/storage 2>/dev/null || true
chown -R sail:sail /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage 2>/dev/null || true
chmod -R 775 /var/www/html/bootstrap/cache 2>/dev/null || true

# Fix /tmp/laravel_views permissions (compiled views)
mkdir -p /tmp/laravel_views
chown -R sail:sail /tmp/laravel_views 2>/dev/null || true
chmod -R 775 /tmp/laravel_views 2>/dev/null || true

# Create log directories if they don't exist
mkdir -p /var/log/supervisor
mkdir -p /var/www/html/storage/logs

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
