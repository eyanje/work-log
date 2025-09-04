#!/bin/sh

REPOSITORY=harbor.eyanje.net/work-log
TAG=$(git rev-parse HEAD)

docker run --rm $REPOSITORY/development:$TAG composer run test
