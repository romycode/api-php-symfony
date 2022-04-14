#!/bin/sh

# Tune to your application needs

# Execute migrations
php /opt/app/bin/console doctrine:migrations:migrate -n

# Execute fixtures
# php /opt/app/bin/console doctrine:fixtures:load -n
