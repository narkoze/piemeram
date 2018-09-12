#!/usr/bin/env bash
cd "$(dirname $0)/.."

./bin/cleanup.sh

composer install --no-dev

yarn --production

npm run production

php artisan vue-i18n:generate
php artisan config:cache
php artisan config:clear
php artisan route:cache
php artisan route:clear

cp -rf ../piem_prod.env .env

# Manipulations for shared hosting
if [ -d public ]; then
  mv public public_html
  sed -i '/\workaround/s/^\/\///g' app/Providers/AppServiceProvider.php
fi
tar -zcvf piem.tgz .
ls -l --block-size=M | grep piem.tgz
scp piem.tgz piem:/home/piemeram/
ssh piem touch /home/piemeram/storage/framework/down
ssh piem tar -xvzf /home/piemeram/piem.tgz
ssh piem rm /home/piemeram/storage/framework/down /home/piemeram/piem.tgz
# php artisan migrate --pretend

cd -
