FROM php:7.3-fpm
MAINTAINER Sven Gehring <cbrxde@gmail.com>

# Install PHP with MySQL
RUN docker-php-ext-install pdo_mysql

# Install GIT
RUN apt update
RUN apt install -y git

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer