FROM php:8-fpm-alpine

USER root

RUN apk -U update                                       \
    && apk add --quiet --no-cache                       \
                            curl                        \
                            postgresql-dev              \
                            make                        \
    && docker-php-ext-configure intl                    \
    && docker-php-ext-install -j$(nproc)                \
       pgsql                                            \
       pdo_pgsql                                        \
    && curl -sO https://gordalina.github.io/cachetool/downloads/cachetool.phar  \
    && chmod a+x cachetool.phar                                                 \
    && mv cachetool.phar /usr/local/bin/cachetool                               \
    && curl -sS https://getcomposer.org/installer                   \
       | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p var/cache var/logs vendor
COPY bin bin
COPY config config
COPY public public
COPY src src
COPY composer.json composer.json
COPY composer.lock composer.lock
COPY symfony.lock symfony.lock
COPY .env.production .env

RUN composer install --no-dev --optimize-autoloader
RUN php bin/console cache:clear --env=prod

COPY ./docker/php-8-fpm/prod/rootfs /
WORKDIR /opt/app
RUN chown -R www-data:www-data /opt/app
USER www-data