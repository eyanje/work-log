#!/bin/sh

set -e

if [ $ENV_FILE ]; then
    cp $ENV_FILE .env
fi

kubectl apply -k .
