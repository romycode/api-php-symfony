#!/usr/bin/env sh
set -ex

envsubst </opt/cors.template >/etc/nginx/h5bp/cross-origin/requests.conf

exec "$@"
