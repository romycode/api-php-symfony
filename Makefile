SHELL = /usr/bin/bash

export HOST_IP := $(shell ip -4 addr show docker0 | grep -Po 'inet \K[\d.]+')
export COMPOSE_PROJECT_NAME = template-api
export XDEBUG_MODE = debug

ENV = dev
API_SERVICE=fpm

COMPOSE = docker compose
COMPOSE_PRO = docker compose -f compose.yaml


.SILENT:

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

build:
	$(COMPOSE) build --build-arg HOST_IP=$(HOST_IP)
	$(MAKE) dev/composer/install

build/pro:
	$(COMPOSE_PRO) build

db/migrate:
	$(MAKE) dev/bin/console/"m:m --allow-no-migration --no-interaction --all-or-nothing"

db/new/migration:
	$(MAKE) dev/bin/console/"m:g"

db/delete:
	$(COMPOSE) exec mysql mysql --protocol=tcp -uroot -ppassword -e 'DROP DATABASE IF EXISTS template; DROP DATABASE IF EXISTS template_test; CREATE DATABASE template; CREATE DATABASE template_test;'

db/reset: db/delete db/migrate

test/unit:
	$(COMPOSE) run --no-deps --rm ${API_SERVICE} vendor/bin/phpunit --order-by=random --testsuite Unit

test/integration:
	$(MAKE) dev/codecept/Integration

test/functional:
	$(MAKE) dev/codecept/Functional

test: tests/unit tests/integration tests/functional

dev/bash:
	$(COMPOSE) exec -u 1000:1000 ${API_SERVICE} bash || true

dev/composer/%:
	$(COMPOSE) run --no-deps --rm -u 1000:1000 ${API_SERVICE} composer $* --optimize-autoloader

dev/codecept/%:
	$(COMPOSE) run --rm -u 1000:1000 ${API_SERVICE} vendor/bin/codecept run --ext DotReporter $*

dev/format:
	$(COMPOSE) run --no-deps --rm ${API_SERVICE} vendor/bin/php-cs-fixer --config=.php-cs-fixer.dist.php f

dev/analyse:
	$(COMPOSE) run --no-deps --rm ${API_SERVICE} vendor/bin/psalm --config=.php-cs-fixer.dist.php f

dev/bin/console/%:
	$(COMPOSE) run ${API_SERVICE} bin/console --env=$(ENV) $*
