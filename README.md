# sonata-platform

## Prerequisites

- PHP >= 7.2
- Laravel 6
- MariaDB 10.3
- Node & NPM
- Composer

---

## Installation

__1. Install Symfony + Sonata platform__

```bash
# 1.0 Install sonata platform
git clone https://github.com/john-dufrene-dev/sonata-platform.git

# x.0 generate the SSH keys
mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout


-- TODO LIST --

1. Create Media CRUD Controller Admin
2. Change User CRUD Controller Admin
3. Try to do custom template knpmenu with  
- `{{ knp_menu_render('main', {'template': 'AcmeBundle:Menu:knp_menu.html.twig'}) }}`

