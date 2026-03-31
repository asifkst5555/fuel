#!/bin/bash

set -euo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"

cd "$APP_ROOT"

if ! "$PHP_BIN" -r "exit(version_compare(PHP_VERSION, '8.3.0', '>=') ? 0 : 1);"; then
    echo "PHP 8.3 or newer is required."
    echo "Select PHP 8.3+ in cPanel MultiPHP Manager before deploying."
    exit 1
fi

if [ ! -f ".env" ]; then
    echo "Missing .env file in $APP_ROOT"
    echo "Create .env in cPanel first, then deploy again."
    exit 1
fi

if ! grep -q '^APP_KEY=base64:' .env; then
    echo "APP_KEY is missing from .env"
    echo "Run: php artisan key:generate --force"
    exit 1
fi

"$COMPOSER_BIN" install --no-dev --optimize-autoloader --no-interaction

"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan migrate --force
"$PHP_BIN" artisan storage:link || true
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

if [ "${RUN_SEED:-false}" = "true" ]; then
    "$PHP_BIN" artisan db:seed --force
fi
