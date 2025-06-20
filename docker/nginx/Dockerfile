# nginx Dockerfile
# The nginx container is responsible for serving static content and JavaScript files.

FROM debian:bookworm AS builder

RUN apt-get update \
	&& apt-get install -y \
	composer \
	curl \
	npm \
	php-laravel-framework \
	unzip \
	&& apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www
COPY . /var/www

RUN composer install --no-dev --optimize-autoloader --no-interaction \
	--no-progress --prefer-dist

RUN npm install && npm run build

# Server
# Serves the built index.php and JS files
FROM nginx:alpine

COPY --from=builder /var/www/public /var/www/public
WORKDIR /var/www/public

COPY ./docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

