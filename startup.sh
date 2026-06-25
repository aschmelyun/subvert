#!/bin/sh

if [ -z "$AI_PROVIDER" ]; then
    AI_PROVIDER="openai"
fi

if [ "$AI_PROVIDER" = "openai" ] && [ -z "$OPENAI_API_KEY" ]; then
    echo "OPENAI_API_KEY is not set"
    exit 1
fi

if [ "$AI_PROVIDER" = "twelvelabs" ] && [ -z "$TWELVELABS_API_KEY" ]; then
    echo "TWELVELABS_API_KEY is not set"
    exit 1
fi

if [ -z "$UPLOAD_MAX_FILESIZE" ]; then
    UPLOAD_MAX_FILESIZE="256M"
fi

if [ -z "$MEMORY_LIMIT" ]; then
    MEMORY_LIMIT="512M"
fi

# Only add these lines if they're not present in the .env file
grep -qxF "AI_PROVIDER=$AI_PROVIDER" .env || echo "AI_PROVIDER=$AI_PROVIDER" >> .env

if [ -n "$OPENAI_API_KEY" ]; then
    grep -qxF "OPENAI_API_KEY=$OPENAI_API_KEY" .env || echo "OPENAI_API_KEY=$OPENAI_API_KEY" >> .env
fi

if [ -n "$TWELVELABS_API_KEY" ]; then
    grep -qxF "TWELVELABS_API_KEY=$TWELVELABS_API_KEY" .env || echo "TWELVELABS_API_KEY=$TWELVELABS_API_KEY" >> .env
fi

# Only add these lines if they're not present in the php.ini file
grep -qxF "memory_limit = $MEMORY_LIMIT" /usr/local/etc/php/php.ini || echo "memory_limit = $MEMORY_LIMIT" >> /usr/local/etc/php/php.ini
grep -qxF "upload_max_filesize = $UPLOAD_MAX_FILESIZE" /usr/local/etc/php/php.ini || echo "upload_max_filesize = $UPLOAD_MAX_FILESIZE" >> /usr/local/etc/php/php.ini
grep -qxF "post_max_size = $UPLOAD_MAX_FILESIZE" /usr/local/etc/php/php.ini || echo "post_max_size = $UPLOAD_MAX_FILESIZE" >> /usr/local/etc/php/php.ini

php artisan key:generate

php artisan migrate:fresh --force

php artisan queue:work &

php artisan serve --port=8080 --host=0.0.0.0