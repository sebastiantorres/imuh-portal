FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# 1) Install system dependencies, PHP extensions, Node.js and npm
RUN apk add --no-cache \
    icu-dev \
    openssl-dev \
    bash \
    libzip-dev \
    oniguruma-dev \
    zip \
    unzip \
    nodejs npm \
  && docker-php-ext-install pdo pdo_mysql mbstring zip intl bcmath pcntl

# 2) Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# 3) Copy application source
COPY portal/. .

# 4) Install PHP dependencies without running scripts
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# 5) Prepare Tailwind input and directories
RUN mkdir -p resources/css public/css \
    && printf "%s\n" "@tailwind base;" "@tailwind components;" "@tailwind utilities;" > resources/css/app.css

# 6) Install JS dependencies and build assets with Vite
RUN npm install && \
    npm run build

# 7) Run Composer post-install scripts now that artisan is present now that artisan is present now that artisan is present
RUN composer run-script post-autoload-dump

# 8) Cache Laravel config, routes and views
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# 9) Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# 10) Expose FPM port and start
EXPOSE 9000
CMD ["php-fpm"]
