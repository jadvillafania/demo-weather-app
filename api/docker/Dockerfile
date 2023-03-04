FROM php:8.1-apache

RUN apt-get update

RUN apt-get install -y \
    pkg-config  \
    libzip-dev 

RUN docker-php-ext-install \
    mysqli \
    pdo_mysql \
    zip \
    opcache

# 0 for production
# 1 for development
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"
ADD opcache.ini "$PHP_INI_DIR/conf.d/opcache.ini"

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite
RUN service apache2 restart

COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container
RUN /usr/local/bin/start-container
