#!/usr/bin/env bash
cd "$(dirname $0)/.."
./bin/cleanup.sh

composer install

yarn
yarn run dev

cd -
