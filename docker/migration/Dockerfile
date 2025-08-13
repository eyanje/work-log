ARG REPOSITORY
ARG BASE_TAG=latest

FROM ${REPOSITORY}/base:${BASE_TAG} AS builder

COPY ./docker/migration/entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

