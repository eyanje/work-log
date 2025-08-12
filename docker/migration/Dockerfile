ARG REPOSITORY
ARG BUILDER_TAG=latest

FROM work-log-builder:${BUILDER_TAG} AS builder

COPY ./docker/migration/entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

