# Based on the instructions in
# https://docs.docker.com/guides/frameworks/laravel/production-setup/

ARG REPOSITORY
ARG BUILDER_TAG=latest

FROM work-log-builder:${BUILDER_TAG} AS builder

RUN apt-get update && apt-get install -y \
	php-fpm

COPY ./docker/php-fpm/www.conf /etc/php/8.2/fpm/pool.d/www.conf

COPY ./docker/php-fpm/entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm8.2", "-F"]
