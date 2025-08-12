#!/usr/bin/sh

php artisan migrate --force &&
    php artisan optimize &&
    php artisan config:cache &&
    php artisan event:cache &&
    php artisan route:cache &&
    php artisan view:cache

