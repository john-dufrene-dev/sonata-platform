# UPDATE DATABASE/PACKAGES (new commit)

__1. Updates project__

```bash

# 1.0 update git repository with last version
git pull (can test with git fetch to see if the repository is correct)

# 1.1 refresh packages
composer install && npm install && yarn encore production (never use composer update/yarn upgrade in production)

# 1.2 clear database
php bin/console doctrine:database:drop (--force)

# 1.3 create database
php bin/console doctrine:database:create

# 1.4 Install database
php bin/console doctrine:migrations:migrate --force

# 1.5 Insert fixtures
php bin/console doctrine:fixtures:load

# 1.6 Modify user admin
Go to admin and modify default useradmin (login: admin / pass: admin)

# 1.7 clear cache
php bin/console cache:clear

```