ToDoList
Codacy Badge
https://app.codacy.com/project/muhammedinandev/projet8-TodoList/dashboard

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

Setup Download Composer dependencies

Make sure you have Composer installed and then run:

composer install Setup the Database

First check parameters.yml is setup for your computer. Then, create the database & tables!

php bin/console doctrine:database:create php bin/console doctrine:schema:update --force php bin/console doctrine:fixtures:load Setup the Test Database

First check config_test.yml

php bin/console doctrine:database:create --env=test php bin/console doctrine:schema:update --force --env=test php bin/console doctrine:fixtures:load --env=test Start server

php bin/console server:run Now check out at http://localhost:8000

Tests

php vendor/bin/phpunit or php vendor/bin/phpunit --coverage-html web/test-coverage

Contribute

See Contribute.md

Documentation

Go to the 'documentation' directory to get the doc and also quality and performances reports.
