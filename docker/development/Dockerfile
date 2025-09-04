ARG REPOSITORY
ARG BASE_TAG=latest

FROM ${REPOSITORY}/base:${BASE_TAG} AS builder

RUN apt-get update && apt-get install -y \
    php-sqlite3 \
	&& apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN composer install --no-interaction \
		--no-progress --prefer-dist

# Copies .env.example, which enables debug mode
RUN composer run post-root-package-install

# Initializes database
RUN composer run post-create-project-cmd
