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

# 1.5 Modify user admin
Go to admin and modify default admin (login: admin / pass: admin)

# 1.6 generate the SSH keys for api
mkdir -p config/jwt # if not exist
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

```

__2. Use symfony serve__

```bash

If you are in local env, install symfony console commands and use php bin/console symfony serve
https://symfony.com/doc/current/setup/symfony_server.html


```

## TODO

__1. Todo list__

```bash

- Create Media CRUD Controller Admin
- Upload Api platform
- Custom CRUDController in sonata admin for SEO
- All missing translations (IN PROGRESS)
- Custom Media and User CSS
- Finish all api for user (IN PROGRESS)
- Create Command to install project
- Create Fixtures for user/admin (IN PROGRESS)
- SEO default pages (IN PROGRESS)

```

__2. Finish todo list__

```bash

- ~~Encrypt password for Api platform~~
- ~~Default send mails with gmail~~
- ~~Change User CRUD Controller Admin~~
- ~~Create table configuration/fixtures~~
- ~~Create Maintenance system~~

```
## INFORMATIONS

__1. Troubleshooting__

```bash

- To upload youtube video https is required (Google API)
- Error return JSON response when upload media in CKEditorType ref: "#1" in 'App\Controller\Admin\Media\CustomUploadCKEditorController'

```

__2. Using dev version for__

```bash

- sonata-project/admin-bundle
- sonata-project/google-authenticator
- sonata-project/media-bundle
- sonata-project/seo-bundle
- sonata-project/user-bundle

```

__3. Suggested packages__

```bash

- simplethings/entity-audit-bundle

