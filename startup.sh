#!/bin/sh

if [ -z "$OPENAI_API_KEY" ]; then
    echo "OPENAI_API_KEY is not set"
    exit 1
fi

if [ -z "$UPLOAD_MAX_SIZE" ]; then
    UPLOAD_MAX_SIZE="256M"
fi

if [ -z "$MEMORY_LIMIT" ]; then
    MEMORY_LIMIT="512M"
fi

echo "OPENAI_API_KEY=$OPENAI_API_KEY" >> .env

echo "memory_limit = $MEMORY_LIMIT" >> /usr/local/etc/php/php.ini
echo "upload_max_filesize = $UPLOAD_MAX_SIZE" >> /usr/local/etc/php/php.ini
echo "post_max_size = $UPLOAD_MAX_SIZE" >> /usr/local/etc/php/php.ini

php artisan key:generate

php artisan migrate:fresh --force

php artisan queue:work &

php artisan serve --port=80 --host=0.0.0.0