FROM docker.io/debian:trixie-slim AS base

WORKDIR /var/www

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

# Install composer vendor files

FROM base AS composer-install

COPY --chown=www-data:www-data composer.json .
COPY --chown=www-data:www-data composer.lock .
RUN composer install --no-dev --no-scripts --optimize-autoloader \
    --no-interaction --no-progress --prefer-dist

# Install node_modules

FROM base AS npm-install

COPY --chown=www-data:www-data package.json .
COPY --chown=www-data:www-data package-lock.json .
RUN npm install

FROM base

COPY --from=composer-install /var/www/vendor ./vendor
COPY --from=npm-install /var/www/node_modules ./node_modules

COPY --chown=www-data:www-data . /var/www

RUN composer install --no-dev --no-interaction \
    --no-progress --prefer-dist

RUN VITE_APP_NAME=${VITE_APP_NAME} npm run build

