#!/usr/bin/env bash
cd "$(dirname $0)/.."
./bin/cleanup.sh

composer install --no-dev
npm install --production
npm run production
php artisan config:cache
php artisan route:cache
php artisan optimize

cd -
