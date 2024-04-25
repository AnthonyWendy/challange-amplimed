FROM php:7.4-apache
RUN docker-php-ext-install mysqli

RUN apt-get update && apt-get install -y --no-install-recommends default-mysql-client

RUN mkdir -p /usr/src
WORKDIR /usr/src

COPY . /usr/src
