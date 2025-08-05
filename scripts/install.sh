#!/usr/bin/sh

docker build -t work-log-builder -f ./docker/builder/Dockerfile .
docker build -t work-log-web -f ./docker/nginx/Dockerfile .
docker build -t work-log-php-fpm -f ./docker/php-fpm/Dockerfile .

