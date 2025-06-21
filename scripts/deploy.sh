#!/usr/bin/sh

docker compose -f compose.prod.yaml -f compose.override.prod.yaml --env-file .env up
