SHELL = /usr/bin/bash

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
	$(COMPOSE) build
	$(MAKE) dev/composer/install

build/pro:
	$(COMPOSE_PRO) build

db/migrate:
	$(MAKE) ENV=dev dev/bin/console/"m:m --allow-no-migration --no-interaction --all-or-nothing"
	$(MAKE) ENV=test dev/bin/console/"m:m --allow-no-migration --no-interaction --all-or-nothing"

db/new/migration:
	$(MAKE) dev/bin/console/"m:g"

db/create:
	$(COMPOSE) exec mysql mysql --protocol=tcp -uroot -ppassword -e 'CREATE DATABASE template; CREATE DATABASE template_test;'

db/delete:
	$(COMPOSE) exec mysql mysql --protocol=tcp -uroot -ppassword -e 'DROP DATABASE IF EXISTS template; DROP DATABASE IF EXISTS template_test;'

db/reset: db/delete db/create db/migrate

test/unit:
	$(COMPOSE) run --no-deps --rm ${API_SERVICE} vendor/bin/phpunit --order-by=random --testsuite Unit

test/integration:
	$(MAKE) dev/codecept/Integration

test/functional:
	$(MAKE) dev/codecept/Functional

test: test/unit test/integration test/functional

dev/bash:
	$(COMPOSE) exec -u 1000:1000 ${API_SERVICE} bash || true

dev/composer/%:
	$(COMPOSE) run --no-deps --rm -u 1000:1000 ${API_SERVICE} composer $* --optimize-autoloader

dev/codecept/%:
	$(COMPOSE) run --rm -u 1000:1000 ${API_SERVICE} vendor/bin/codecept run --ext DotReporter $*

dev/format:
	$(COMPOSE) run --no-deps --rm ${API_SERVICE} vendor/bin/php-cs-fixer --config=.php-cs-fixer.dist.php f

dev/analyse:
	$(COMPOSE) run --no-deps --rm ${API_SERVICE} vendor/bin/psalm --config=psalm.xml

dev/bin/console/%:
	$(COMPOSE) run ${API_SERVICE} bin/console --env=$(ENV) $*
