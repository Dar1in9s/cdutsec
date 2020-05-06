FROM php:7.2-apache

COPY html /var/www/html/

RUN chown -R root:root /var/www/html &&\
    chmod -R 755  /var/www/html &&\
	chmod 766 /var/www/html/flag.dat

EXPOSE 80

