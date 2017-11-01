#!/usr/bin/env bash
cd "$(dirname $0)/.."
./bin/cleanup.sh

composer install --no-dev

yarn --production

npm run production

php artisan config:cache
php artisan config:clear
php artisan route:cache
php artisan route:clear
php artisan optimize

cd -
