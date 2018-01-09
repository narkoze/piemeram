#!/usr/bin/env bash
cd "$(dirname $0)/.."

./artisan serve & npm run watch
