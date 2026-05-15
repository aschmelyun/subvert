#!/bin/bash
set -euo pipefail

if [ "${CLAUDE_CODE_REMOTE:-}" != "true" ]; then
  exit 0
fi

cd "$CLAUDE_PROJECT_DIR/src"

# Install PHP dependencies (--ignore-platform-reqs needed as lock file predates PHP 8.4)
composer install --no-interaction --prefer-dist --ignore-platform-reqs

# Install JS dependencies
npm install

# Set up .env if not present
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Generate app key if not set
php artisan key:generate --no-interaction --force

# Ensure SQLite database file exists
touch database/database.sqlite

# Run migrations
php artisan migrate --force --no-interaction

# Build frontend assets
npm run build
