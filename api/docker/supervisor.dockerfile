FROM php:8.1-apache

RUN apt-get update && apt-get install -y supervisor
RUN docker-php-ext-install pdo pdo_mysql

COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisor.conf"]