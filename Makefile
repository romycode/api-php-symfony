DOCKER_IP=$(shell ip -4 addr show docker0 | grep -Po 'inet \K[\d.]+')

API_SERVICE=php_fpm

docker/up:
	@COMPOSE_PROJECT=template-api HOST_IP=${DOCKER_IP} docker compose up -d

docker/down:
	@COMPOSE_PROJECT=template-api HOST_IP=${DOCKER_IP} docker compose down

docker/build:
	@HOST_IP=${DOCKER_IP} docker compose build
	@make docker/deps

docker/deps:
	@HOST_IP=${DOCKER_IP} docker compose run --no-deps --rm ${API_SERVICE} composer install --optimize-autoloader

db/migrate:
	@docker compose run --no-deps --rm ${API_SERVICE} bin/console m:m --env="dev"  --allow-no-migration --no-interaction --all-or-nothing
	@docker compose run --no-deps --rm ${API_SERVICE} bin/console m:m --env="test" --allow-no-migration --no-interaction --all-or-nothing

db/delete:
	@docker compose exec template_db mysql -uroot -ppassword -e 'DROP DATABASE template; DROP DATABASE template_test; CREATE DATABASE template; CREATE DATABASE template_test;'

db/reset: db/delete db/migrate

tests/unit:
	@HOST_IP=${DOCKER_IP} docker compose run --rm ${API_SERVICE} vendor/bin/phpunit --order-by=random --testsuite Unit

tests/integration:
	@HOST_IP=${DOCKER_IP} docker compose run --rm ${API_SERVICE} vendor/bin/codecept run Integration

tests/functional:
	@HOST_IP=${DOCKER_IP} docker compose run --rm ${API_SERVICE} vendor/bin/codecept run Functional

tests: tests/unit tests/integration tests/functional

bash:
	@(HOST_IP=${DOCKER_IP} docker compose run --no-deps --rm ${API_SERVICE} bash) || true

format:
	@HOST_IP=${DOCKER_IP} docker compose run --rm ${API_SERVICE} vendor/bin/php-cs-fixer f