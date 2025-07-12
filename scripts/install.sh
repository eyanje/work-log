#!/usr/bin/sh

docker build -t work-log-builder -f ./docker/builder/Dockerfile . \
	&& docker compose -f compose.prod.yaml build

