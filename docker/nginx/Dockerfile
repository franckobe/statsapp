FROM nginx:stable-alpine
LABEL maintainer="Franck GARROS"

ENV APP_ENV=$APP_ENV

# Copy the nginx configuration file
COPY ./docker/nginx/conf/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/conf/default.conf /etc/nginx/conf.d/default.conf
RUN if [ "$APP_ENV" = "prod" ]; then \
      cp ./docker/nginx/conf/ssl.conf /etc/nginx/conf.d/ssl.conf; \
    fi

# Update and upgrade the system
RUN apk update && apk upgrade

RUN apk update && apk add iputils procps

# Create the www-data user and group
RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1 \

RUN chown -R www-data:www-data /var/www

ARG PHP_UPSTREAM_CONTAINER=php
ARG PHP_UPSTREAM_PORT=9000

RUN echo "upstream php-upstream { server ${PHP_UPSTREAM_CONTAINER}:${PHP_UPSTREAM_PORT}; }" > /etc/nginx/conf.d/upstream.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
