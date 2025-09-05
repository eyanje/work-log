# Based on the instructions in
# https://docs.docker.com/guides/frameworks/laravel/production-setup/

ARG REPOSITORY
ARG BASE_TAG=latest

FROM ${REPOSITORY}/base:${BASE_TAG}

RUN apt-get update && apt-get install -y \
    libfcgi-bin \
	php-fpm

COPY ./docker/php-fpm/php-fpm.conf /etc/php/8.4/fpm/php-fpm.conf
COPY ./docker/php-fpm/www.conf /etc/php/8.4/fpm/pool.d/www.conf

EXPOSE 9000
CMD ["php-fpm8.4", "-F"]
