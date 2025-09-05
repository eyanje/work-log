#!/bin/sh

# https://stackoverflow.com/questions/2870992/automatic-exit-from-bash-shell-script-on-error#2871034
set -e

REPOSITORY=harbor.eyanje.net/work-log
TAG=$(git rev-parse HEAD)

for PARAM in $*; do
    if [ $PARAM = "--registry-cache" ]; then
        CACHE_ARGS="$CACHE_ARGS --cache-from=$REPOSITORY/build-cache"
        CACHE_ARGS="$CACHE_ARGS --cache-to=$REPOSITORY/build-cache"
    fi
done

docker build \
    --network host \
    $CACHE_ARGS \
	-t $REPOSITORY/build-agent:$TAG \
	-t $REPOSITORY/build-agent:latest \
	-f ./docker/build-agent/Dockerfile .
docker build \
    --network host \
    $CACHE_ARGS \
	-t $REPOSITORY/base:$TAG \
	-t $REPOSITORY/base:latest \
	-f ./docker/base/Dockerfile .
docker build \
    --network host \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
    $CACHE_ARGS \
	-t $REPOSITORY/development:$TAG \
	-t $REPOSITORY/development:latest \
	-f ./docker/development/Dockerfile .
docker build \
    --network host \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
    $CACHE_ARGS \
	-t $REPOSITORY/migration:$TAG \
	-t $REPOSITORY/migration:latest \
	-f ./docker/migration/Dockerfile .
docker build \
    --network host \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
    $CACHE_ARGS \
	-t $REPOSITORY/web:$TAG \
	-t $REPOSITORY/web:latest \
	-f ./docker/web/Dockerfile .
docker build \
    --network host \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
    $CACHE_ARGS \
	-t $REPOSITORY/php-fpm:$TAG \
	-t $REPOSITORY/php-fpm:latest \
	-f ./docker/php-fpm/Dockerfile .

