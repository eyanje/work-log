#!/bin/sh

REPOSITORY=harbor.eyanje.net/work-log
TAG=$(git rev-parse HEAD)

docker image push $REPOSITORY/migration:$TAG
docker image push $REPOSITORY/migration:latest
docker image push $REPOSITORY/php-fpm:$TAG
docker image push $REPOSITORY/php-fpm:latest
docker image push $REPOSITORY/web:$TAG
docker image push $REPOSITORY/web:latest

