FROM php:5.6
RUN docker-php-ext-install pdo pdo_mysql
RUN mkdir /app
VOLUME /app
WORKDIR /app/public
EXPOSE 10000
CMD php -S 0.0.0.0:10000 