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

ssh piem 'sh /home/piemeram/cleanup.sh'
tar czv public_html | ssh piem 'cat | tar xz -C /home/piemeram/'
tar czv \
  --exclude public_html \
  --exclude database \
  --exclude bin \
  --exclude tests \
  --exclude artisan \
  --exclude .git \
  --exclude .vscode \
  --exclude .editorconfig \
  --exclude .env.example \
  --exclude .eslintrc.json \
  --exclude .gitattributes \
  --exclude .gitignore \
  --exclude composer.json \
  --exclude composer.lock \
  --exclude yarn.lock \
  --exclude package-lock.json \
  --exclude package.json \
  --exclude phpunit.xml \
  --exclude README.md \
  --exclude server.php \
  --exclude webpack.mix.js \
./ | ssh piem 'cat | tar xz -C /home/piemeram/laravel'
ssh piem 'sh /home/piemeram/indexfix.sh'


# php artisan migrate --pretend

cd -
