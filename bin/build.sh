#!/usr/bin/env bash
cd "$(dirname $0)/.."
./bin/cleanup.sh

composer install
yarn

cp -rf ../piem.env .env

cd -
