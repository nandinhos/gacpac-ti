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
COPY composer.json composer.lock package.json package-lock.json ./

# Install PHP dependencies (as root, sem scripts pois artisan nao esta disponivel ainda)
RUN composer install --optimize-autoloader --no-scripts --no-dev

# Copy application files
COPY . .

# Set permissions — tudo pertence ao sail antes de qualquer operacao NPM
RUN chown -R sail:sail /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Configurar npm cache para o usuario sail (evita conflitos de permissao no runtime)
RUN mkdir -p /home/sail/.npm \
    && chown -R sail:sail /home/sail/.npm

# Switch to sail user ANTES de qualquer operacao npm
USER sail

# Install Node dependencies como sail user (node_modules fica com owner correto)
RUN npm install

# Build frontend assets como sail user
RUN npm run build

# Voltar para root temporariamente para operacoes de sistema
USER root

# Generate application key and optimize
RUN php artisan package:discover --ansi
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/nginx-main.conf /etc/nginx/nginx.conf

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy and setup entrypoint script
COPY docker/start-container.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

# Prepare directories for rootless execution
RUN mkdir -p /var/log/supervisor /var/run /tmp/laravel_views /var/www/html/storage/logs /var/lib/nginx /var/cache/nginx \
    && chown -R sail:sail /var/log/supervisor /var/run /tmp/laravel_views /var/www/html /var/lib/nginx /var/cache/nginx \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /tmp/laravel_views

# Switch definitivo para sail user (sem necessidade de root no runtime)
USER sail

EXPOSE 8000

ENTRYPOINT ["start-container"]
