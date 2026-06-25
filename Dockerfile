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

# Install PHP extensions (tokenizer & fileinfo & opcache are bundled in PHP 8.3)
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

# Set working directory
WORKDIR /app

# Copy composer files first (layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy package files and build assets
COPY package.json package-lock.json ./
RUN npm ci

# Copy all application files
COPY . .

# Run npm build (Vite)
RUN npm run build

# Run composer post-install scripts (no artisan here - env vars not available at build time)
RUN composer run-script post-autoload-dump --no-interaction || true

# Write startup script with guaranteed Unix line endings (no Windows CRLF issues)
RUN printf '#!/bin/sh\n\
php artisan storage:link --force || true\n\
php artisan config:cache || true\n\
php artisan route:cache || true\n\
php artisan view:cache || true\n\
php artisan migrate --force &\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}\n' > /app/entrypoint.sh \
    && chmod +x /app/entrypoint.sh

EXPOSE 8000

CMD ["/bin/sh", "/app/entrypoint.sh"]
