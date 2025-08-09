# nginx Dockerfile
# The nginx container is responsible for serving static content and JavaScript files.

ARG REPOSITORY
ARG BUILDER_TAG=latest

FROM ${REPOSITORY}/work-log-builder:${BUILDER_TAG} AS builder

# Server
# Serves the built index.php and JS files
FROM docker.io/nginx:alpine

COPY --from=builder /var/www/public /var/www/public
WORKDIR /var/www/public

COPY ./docker/web/nginx.conf /etc/nginx/conf.d/default.conf

