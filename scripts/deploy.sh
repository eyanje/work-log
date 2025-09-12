#!/bin/sh

set -ex

if [ $ENV_FILE ]; then
    cp $ENV_FILE .env
fi

kubectl apply -k .

kubectl rollout status statefulset
kubectl rollout restart deployment
kubectl rollout status deployment
