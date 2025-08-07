FROM docker.io/debian:bookworm

RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
	composer \
	curl \
	libpq-dev \
	npm \
	php-curl \
	php-laravel-framework \
	php-pgsql \
	php-redis \
	unzip \
	&& apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www
COPY --chown=www-data:www-data . /var/www

RUN composer install --no-dev --optimize-autoloader --no-interaction \
		--no-progress --prefer-dist

RUN npm install && npm run build

