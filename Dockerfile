FROM php:8.2-alpine

RUN touch /usr/local/etc/php/php.ini

RUN echo "memory_limit = 512M" >> /usr/local/etc/php/php.ini
RUN echo "upload_max_filesize = 128M" >> /usr/local/etc/php/php.ini
RUN echo "post_max_size = 128M" >> /usr/local/etc/php/php.ini

RUN apk add --no-cache ffmpeg

ADD ./src /var/www/html

ADD ./startup.sh /srv/startup.sh

RUN chmod +x /srv/startup.sh

WORKDIR /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer install

RUN apk add --update npm

RUN npm install

RUN npm run build

EXPOSE 80

RUN touch database/database.sqlite

RUN mkdir -p storage/app/audio
RUN mkdir -p storage/app/video

RUN chmod -R 777 storage

CMD ["/srv/startup.sh"]