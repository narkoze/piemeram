#!/usr/bin/env bash
cd "$(dirname $0)/.."
./bin/cleanup.sh

composer install
yarn
npm run dev

cd -
