FROM php:8.3-cli

# System deps + PHP extensions Laravel needs
RUN apt-get update && apt-get install -y --no-install-recommends \
        git curl zip unzip libzip-dev libpq-dev libsqlite3-dev libonig-dev libxml2-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip bcmath mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy source
COPY . .

# PHP deps (no dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Frontend assets
RUN npm ci && npm run build

# Make sure Laravel runtime directories exist and are writable.
# (.dockerignore strips their placeholder contents, so re-create them here.)
RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/testing \
        storage/logs \
        bootstrap/cache \
        database \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database \
    && chown -R www-data:www-data storage bootstrap/cache database

# Render injects PORT at runtime; 10000 is a common default
ENV PORT=10000
EXPOSE 10000

# Migrate (non-blocking if no DB yet) then serve
CMD php artisan config:cache \
    && php artisan route:cache \
    && php artisan migrate --force --no-interaction || true \
    && php artisan serve --host=0.0.0.0 --port=${PORT}
