#!/usr/bin/env bash
cd "$(dirname $0)/.."

./bin/cleanup.sh

composer install --no-dev

php artisan vue-i18n:generate
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

yarn install --prod
yarn run prod

cd -
