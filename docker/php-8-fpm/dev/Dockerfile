FROM php:8.2-fpm-alpine

ENV DOCKER_IP ""
ENV XDEBUG_CONFIG "client_host=${DOCKER_IP}"

WORKDIR /opt/app

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer/composer /usr/bin/composer /usr/local/bin/

COPY ./docker/nginx/dev/rootfs /

RUN mkdir -p var/cache var/logs vendor      ;\
    apk update --no-cache --quiet           ;\
    apk add --no-cache --quiet bash sudo    ;\
    install-php-extensions intl pdo_mysql

RUN chmod -R 766 /opt/app ; chmod 777 var/logs var/cache