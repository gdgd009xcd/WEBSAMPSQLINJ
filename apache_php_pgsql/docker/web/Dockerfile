FROM php:7.1-apache
COPY ./php.ini /usr/local/etc/php/
COPY ./cgi.* /etc/apache2/mods-enabled/
RUN mkdir -p /home/tmp && cd /home ; chown www-data:www-data tmp
RUN set -ex apk --no-cache add postgresql-dev libpq-dev && \
    apt-get update && apt-get install -y libpq-dev \
    vim \
    less \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring
