FROM php:8-fpm-alpine

ENV XDEBUG_SESSION=docker
ENV HOST_IP=${HOST_IP}

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del -f .build-deps

RUN apk add --no-cache bash git \
  && docker-php-ext-install pdo_mysql \
  && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && php -r "unlink('composer-setup.php');"

COPY ./docker/php-8-fpm/dev/rootfs            /
COPY ./docker/php-8-fpm/dev/init.sh           /usr/local/bin/init.sh
COPY ./docker/php-8-fpm/dev/wait-for-it.sh    /usr/local/bin/wait-for-it.sh

RUN mkdir -p /opt/app/var/cache /opt/app/var/log \
  && echo "xdebug.client_host=$HOST_IP" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /opt/app
