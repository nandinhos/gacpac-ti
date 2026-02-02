FROM php:8.4-fpm

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    nginx \
    supervisor \
    nodejs \
    npm

ARG WWWUSER=1000
ARG WWWGROUP=1000

RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER sail

# Configure PHP-FPM to run as sail user
RUN sed -i "s/user = www-data/user = sail/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = sail/g" /usr/local/etc/php-fpm.d/www.conf

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy dependency files
COPY composer.json composer.lock package.json ./

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-scripts --no-dev

# Install Node dependencies
RUN npm install

# Copy application files
COPY . .

# Set permissions
RUN chown -R sail:sail /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Generate application key and optimize
RUN php artisan package:discover --ansi
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Build frontend assets
RUN npm run build

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy and setup entrypoint script
COPY docker/start-container.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 80

ENTRYPOINT ["start-container"]
