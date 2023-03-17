#!/usr/bin/env bash

exec chown -R www-data:www-data /var/www/html
# echo "ok" >> /var/www/html/test

exec "$@"