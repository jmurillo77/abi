#!/bin/bash
set -e

cd /var/www/ions

if [ -d node_modules ]; then
    npm run build
else
    npm install
    npm run build
fi

php artisan migrate --force
php artisan db:seed --force
