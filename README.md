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

1. Create Media CRUD Controller Admin
2. Change User CRUD Controller Admin
3. Api platform tutorial
4. Encrypt password for Api platform
5. Upload Api platform
6. Custom CRUDController in sonata admin for SEO

