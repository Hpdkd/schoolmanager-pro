#!/bin/sh

echo "[start.sh] Linking storage..."
php artisan storage:link --force || true

echo "[start.sh] Caching config/routes/views..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "[start.sh] Running DB migrations in background..."
php artisan migrate --force &

echo "[start.sh] Starting HTTP server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
