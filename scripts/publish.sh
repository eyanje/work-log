#!/bin/sh

REPOSITORY=harbor.eyanje.net/work-log
TAG=$(git rev-parse HEAD)

docker image push $REPOSITORY/work-log-web:$TAG
docker image push $REPOSITORY/work-log-web:latest
docker image push $REPOSITORY/work-log-php-fpm:$TAG
docker image push $REPOSITORY/work-log-php-fpm:latest

