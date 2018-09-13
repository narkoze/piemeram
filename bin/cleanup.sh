#!/usr/bin/env bash
cd "$(dirname $0)/.."
rm -rf ./bootstrap/cache/*
rm -rf ./vendor
rm -rf ./node_modules
rm -rf ./public/js/*
rm -rf ./public/css/*
rm -rf ./public/fonts/*
rm -rf ./public/images/*
rm -rf ./storage/debugbar/*
rm -rf piem.tgz

if [ -d public_html ]; then
  mv public_html public
  sed -i '/\workaround/s/^/\/\//g' 'app/Providers/AppServiceProvider.php'
fi
cd -
