#!/usr/bin/sh

REPOSITORY=harbor.eyanje.net/work-log
TAG=$(git rev-parse HEAD)

docker build \
	-t $REPOSITORY/base:$TAG \
	-t $REPOSITORY/base:latest \
	-f ./docker/base/Dockerfile .
docker build \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
	-t $REPOSITORY/migration:$TAG \
	-t $REPOSITORY/migration:latest \
	-f ./docker/migration/Dockerfile .
docker build \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
	-t $REPOSITORY/web:$TAG \
	-t $REPOSITORY/web:latest \
	-f ./docker/web/Dockerfile .
docker build \
	--build-arg REPOSITORY=$REPOSITORY \
	--build-arg BASE_TAG=$TAG \
	-t $REPOSITORY/php-fpm:$TAG \
	-t $REPOSITORY/php-fpm:latest \
	-f ./docker/php-fpm/Dockerfile .

