# UPDATE DATABASE/PACKAGES (new commit)

__1. Updates project__

```bash

# 1.0 update git repository with last version
git pull (can test with git fetch to see if the repository is correct)

# 1.1 refresh packages
composer install && npm install && yarn encore production (never use composer update/yarn upgrade in production)

# 1.2 clear database
php bin/console doctrine:database:drop (--force) (Just run it if it is a new project)

# 1.3 create database
php bin/console doctrine:database:create (Just run it if it is a new project)

# 1.4 Install database
php bin/console doctrine:migrations:migrate

# 1.5 Insert fixtures
php bin/console doctrine:fixtures:load (Just run it if it is a new project)

# 1.6 Modify user admin
Go to admin and modify default admin (login: admin / pass: admin)

# 1.7 clear cache
php bin/console cache:clear

# 1.7 rebuild all cache app
php bin/console cache-app:rebuild (important after a cache clear if you have configure some configuration cache or redirect)

```