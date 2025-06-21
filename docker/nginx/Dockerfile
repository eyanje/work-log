# nginx Dockerfile
# The nginx container is responsible for serving static content and JavaScript files.

FROM work-log-builder AS builder

# Server
# Serves the built index.php and JS files
FROM nginx:alpine

COPY --from=builder /var/www/public /var/www/public
WORKDIR /var/www/public

COPY ./docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

