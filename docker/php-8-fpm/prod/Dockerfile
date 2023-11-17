FROM php:8-fpm-alpine

WORKDIR /opt/app

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer/composer /usr/bin/composer /usr/local/bin/

RUN mkdir -p var/cache var/logs vendor      ;\
    apk update --no-cache --quiet           ;\
    install-php-extensions intl pdo_mysql

COPY ./docker/php-8-fpm/prod/rootfs /
COPY bin bin
COPY config config
COPY public public
COPY src src
COPY composer.json composer.json
COPY composer.lock composer.lock
COPY symfony.lock symfony.lock
COPY .env .env

RUN composer install --no-dev --optimize-autoloader
RUN php bin/console cache:clear --env=prod
