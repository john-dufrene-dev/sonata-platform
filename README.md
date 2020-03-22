# sonata-platform

## Prerequisites

- PHP >= 7.2
- Symfony 4.4
- MariaDB 10.3
- Node & NPM
- Composer
- Yarn

---

## Installation

__1. Install Symfony + Sonata platform__

```bash
# 1.0 Install sonata platform
git clone https://github.com/john-dufrene-dev/sonata-platform.git

# 1.1 Edit your .env
cp .env.example .env

# 1.2 Install packages DEV
composer install && npm install && yarn encore production

# 1.3 Install database
php bin/console doctrine:migrations:migrate --force

# 1.4 Insert fixtures
php bin/console doctrine:fixtures:load

# 1.5 Create user admin
php bin/console fos:user:create --super-admin

# 1.6 generate the SSH keys for api
mkdir -p config/jwt # if not exist
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

## TODO LIST

- Create Media CRUD Controller Admin
- Upload Api platform
- Custom CRUDController in sonata admin for SEO
- All missing translations (IN PROGRESS)
- Custom Media and User CSS
- Finish all api for user (IN PROGRESS)
- Create Command to install project
- Create Fixtures for user/useradmin (IN PROGRESS)

## FINISH TODO LIST

- ~~Encrypt password for Api platform~~
- ~~Default send mails with gmail~~
- ~~Change User CRUD Controller Admin~~
- ~~Create table configuration/fixtures~~
- ~~Create Maintenance system~~

## USING DEV VERSION FOR

- sonata-project/admin-bundle
- sonata-project/google-authenticator
- sonata-project/media-bundle
- sonata-project/seo-bundle

## KNOWING ISSUES

