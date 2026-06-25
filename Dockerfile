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

# Expose port
EXPOSE 8000

# Start: env vars are available here, so cache config/routes/views, link storage, migrate, then serve
CMD php artisan storage:link --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan migrate --seed --force \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
