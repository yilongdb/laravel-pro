FROM php:7.0.4-fpm

MAINTAINER TKing <2974105336@qq.com>
RUN apt-get update && apt-get install -y libmcrypt-dev \
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql

EXPOSE 80
EXPOSE 443
#CMD ["executable","param1","param2"]