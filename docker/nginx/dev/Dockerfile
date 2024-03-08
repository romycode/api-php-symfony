FROM nginx:alpine

RUN apk add --no-cache bash

COPY ./docker/nginx/dev/rootfs /
COPY ./docker/nginx/dev/entrypoint.sh /opt/entrypoint.sh

ENV ESC='$'                             \
    REQUEST_METHOD='$request_method'    \
    NGINX_FPM_ENVIRONMENT=development   \
    NGINX_CORS_ALLOW_ORIGIN=*

ENV NGINX_PHP_TEMPLATE_FPM_PATH=fpm   \
    NGINX_PHP_TEMPLATE_SERVER_ROOT=/opt/app/public     \
    NGINX_PHP_TEMPLATE_SERVER_PORT=8081

EXPOSE 8081

CMD ["nginx","-g", "daemon off;"]
