#!/bin/sh

# https://stackoverflow.com/questions/2870992/automatic-exit-from-bash-shell-script-on-error#2871034
set -e

REPOSITORY=harbor.eyanje.net/work-log
TAG=$(git rev-parse HEAD)

docker build \
    --network host \
	-t $REPOSITORY/build-agent:$TAG \
	-t $REPOSITORY/build-agent:latest \
	-f ./docker/build-agent/Dockerfile .
docker build \
    --network host \
	-t $REPOSITORY/base:$TAG \
	-t $REPOSITORY/base:latest \
	-f ./docker/base/Dockerfile .
docker build \
    --network host \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
	-t $REPOSITORY/development:$TAG \
	-t $REPOSITORY/development:latest \
	-f ./docker/development/Dockerfile .
docker build \
    --network host \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
	-t $REPOSITORY/migration:$TAG \
	-t $REPOSITORY/migration:latest \
	-f ./docker/migration/Dockerfile .
docker build \
    --network host \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
	-t $REPOSITORY/web:$TAG \
	-t $REPOSITORY/web:latest \
	-f ./docker/web/Dockerfile .
docker build \
    --network host \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
	-t $REPOSITORY/php-fpm:$TAG \
	-t $REPOSITORY/php-fpm:latest \
	-f ./docker/php-fpm/Dockerfile .

