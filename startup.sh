#!/bin/sh

cp .env.example .env

echo "OPENAI_API_KEY=$OPENAI_API_KEY" >> .env

php artisan key:generate

php artisan queue:work &

php artisan serve --port=80 --host=0.0.0.0