#!/bin/sh

if [ -z "$OPENAI_API_KEY" ]; then
    echo "OPENAI_API_KEY is not set"
    exit 1
fi

echo "OPENAI_API_KEY=$OPENAI_API_KEY" >> .env

php artisan key:generate

php artisan migrate:fresh --force

php artisan queue:work &

php artisan serve --port=80 --host=0.0.0.0