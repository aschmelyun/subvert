FROM php:8.2-alpine

RUN touch /usr/local/etc/php/php.ini

RUN apk add --no-cache ffmpeg

ADD ./src /var/www

ADD ./startup.sh /srv/startup.sh

RUN chmod +x /srv/startup.sh

WORKDIR /var/www

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer install

RUN apk add --update npm

RUN npm install

RUN npm run build

EXPOSE 8080

RUN touch database/database.sqlite

RUN cp .env.example .env

RUN mkdir -p storage/app/audio
RUN mkdir -p storage/app/video

RUN chmod -R 777 storage

VOLUME ["/var/www/storage/app/audio", "/var/www/storage/app/video"]

CMD ["/srv/startup.sh"]