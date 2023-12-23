php bin/console doctrine:database:create

php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate

symfony serve
php bin/console make:entity   
bin/console make:controller