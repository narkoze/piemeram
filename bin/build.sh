#!/usr/bin/env bash
cd "$(dirname $0)/.."
./bin/cleanup.sh

composer install
yarn

cp -rf ../.env .

./artisan serve & npm run watch

cd -
