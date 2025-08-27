#!/bin/sh

cp "$ENV_FILE" .env
kubectl apply -k .
