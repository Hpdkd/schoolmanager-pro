FROM php:8.3-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    nodejs \
    npm \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    icu-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        xml \
        zip \
        gd \
        intl \
        bcmath \
    && docker-php-ext-enable opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first (layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy package files and build assets
COPY package.json package-lock.json ./
RUN npm ci

# Copy all application files
COPY . .

# Build frontend assets
RUN npm run build

# Run composer post-install scripts
RUN composer run-script post-autoload-dump --no-interaction || true

# Create all required Laravel directories (many are in .gitignore and won't be in repo)
RUN mkdir -p \
    /app/storage/framework/cache/data \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/framework/testing \
    /app/storage/logs \
    /app/storage/app/public \
    /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache || true

# Write startup script with guaranteed Unix LF line endings via printf
RUN printf '#!/bin/sh\n\
php artisan storage:link --force 2>&1 || true\n\
php artisan config:cache 2>&1 || true\n\
php artisan route:cache 2>&1 || true\n\
php artisan view:cache 2>&1 || true\n\
php artisan migrate --force 2>&1 &\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}\n' > /app/entrypoint.sh \
    && chmod +x /app/entrypoint.sh

EXPOSE 8000

CMD ["/bin/sh", "/app/entrypoint.sh"]
