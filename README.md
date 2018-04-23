# Gaondo

https://gaondo.com

## Setup

* `composer install`
* `npm install`
* `php bin/console doctrine:database:create`
* `php bin/console doctrine:migrations:migrate`

## Permissions
The following directories (and everything inside them) should be writable by both httpd and the user running git:
* `var/cache`
* `var/logs`
* `var/sessions`
* `web/images`
* `web/cache`

## Run
* `php bin/console server:run`
* `npm start`
