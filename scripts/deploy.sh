#!/usr/bin/sh

docker compose -f compose.prod.yaml --env-file .env up
