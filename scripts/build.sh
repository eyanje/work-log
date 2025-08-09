#!/usr/bin/sh

REPOSITORY=harbor.eyanje.net/work-log
TAG=$(git rev-parse HEAD)

docker build \
	-t $REPOSITORY/work-log-builder:$TAG \
	-t $REPOSITORY/work-log-builder:latest \
	-f ./docker/builder/Dockerfile .
docker build \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BUILDER_TAG=$TAG \
	-t $REPOSITORY/work-log-web:$TAG \
	-t $REPOSITORY/work-log-web:latest \
	-f ./docker/web/Dockerfile .
docker build \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BUILDER_TAG=$TAG \
	-t $REPOSITORY/work-log-php-fpm:$TAG \
	-t $REPOSITORY/work-log-php-fpm:latest \
	-f ./docker/php-fpm/Dockerfile .

