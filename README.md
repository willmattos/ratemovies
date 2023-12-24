php bin/console doctrine:database:create

php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate

symfony serve
php bin/console make:entity   
bin/console make:controller
php bin/console cache:clear
php bin/console cache:clear --env=dev
php bin/console cache:clear --env=prod


php bin/console cache:clear --env=dev --no-warmup
php bin/console cache:warmup --env=dev

composer install
