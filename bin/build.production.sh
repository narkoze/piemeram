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

cp -rf ../.env .

# Manipulations for shared hosting
if [ -d public ]; then
  mv public public_html
  sed -i '/\workaround/s/^\/\///g' app/Providers/AppServiceProvider.php
fi
tar -zcvf piem.tgz .

cd -
