FROM php:8-cli-alpine

ARG HOST_IP
ENV HOST_IP=${HOST_IP}
ENV XDEBUG_SESSION=docker

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del -f .build-deps

RUN apk add --no-cache bash git \
  && docker-php-ext-install pdo_mysql \
  && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"  \
  && php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && php -r "unlink('composer-setup.php');"

COPY ./docker/php-8-cli/dev/rootfs            /
COPY ./docker/php-8-cli/dev/init.sh           /usr/local/bin/init.sh
COPY ./docker/php-8-cli/dev/wait-for-it.sh    /usr/local/bin/wait-for-it.sh

RUN mkdir -p /opt/app/var/cache /opt/app/var/log \
  && echo "xdebug.client_host=${HOST_IP}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /opt/app
