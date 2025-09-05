#!/bin/sh

# https://stackoverflow.com/questions/2870992/automatic-exit-from-bash-shell-script-on-error#2871034
set -e

REPOSITORY=harbor.eyanje.net/work-log
TAG=$(git rev-parse HEAD)

docker image push $REPOSITORY/build-agent:latest
docker image push $REPOSITORY/base:latest
docker image push $REPOSITORY/development:latest
docker image push $REPOSITORY/migration:latest
docker image push $REPOSITORY/php-fpm:latest
docker image push $REPOSITORY/web:latest

