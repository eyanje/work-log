# Based on the instructions in
# https://docs.docker.com/guides/frameworks/laravel/production-setup/

FROM php:8.2-fpm AS builder

RUN apt-get update && apt-get install -y \
	curl \
	libpq-dev \
	&& docker-php-ext-install \
	pdo_pgsql \
	pgsql \
	&& apt-get autoremove -y \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


WORKDIR /var/www
COPY --chown=www-data:www-data . /var/www

RUN curl -sS https://getcomposer.org/installer \
	| php -- --install-dir=/usr/local/bin --filename=composer \
	&& composer install --no-dev --optimize-autoloader --no-interaction \
		--no-progress --prefer-dist

COPY ./docker/php-fpm/entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]

CMD ["php-fpm"]
