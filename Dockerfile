FROM php:8.4-cli-alpine AS base

# System deps
RUN apk add --no-cache \
    oniguruma-dev \
    libxml2-dev \
    libpng-dev \
    libzip-dev \
    curl-dev \
    unzip \
    git \
    openssl \
    nodejs \
    npm \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        xml \
        gd \
        bcmath \
        zip \
        soap

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# App source (except vendor/node_modules covered by .dockerignore)
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install & build assets
RUN npm install -g pnpm && pnpm install --ignore-scripts && pnpm run build

# Storage bootstrap
RUN mkdir -p storage/framework/{sessions,views,cache,testing} \
    storage/logs \
    storage/app/sunat \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache public

EXPOSE 8000

CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]
