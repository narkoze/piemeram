#!/usr/bin/env bash
cd "$(dirname $0)/.."
./bin/cleanup.sh

composer install
yarn
./artisan serve & npm run watch

cd -
