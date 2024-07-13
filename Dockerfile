FROM webdevops/php-nginx-dev:8.2

ENV WEB_DOCUMENT_ROOT /app/public
ENV WEB_DOCUMENT_INDEX index.php

WORKDIR /app
COPY . /app
RUN chown -R application:www-data /app
USER application
RUN composer install --optimize-autoloader
