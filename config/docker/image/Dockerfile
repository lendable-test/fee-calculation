FROM ubuntu:18.04

MAINTAINER Mikhail Vasilev <mikhail.vasilev@bt.com>

# Update ubuntu
RUN apt-get update && apt-get upgrade -y

# PHP
RUN apt-get install -y software-properties-common
RUN add-apt-repository ppa:ondrej/php
RUN apt-get install -y php7.3-fpm php7.3-xml php7.3-mbstring

# Ant
RUN apt-get install -y ant ant-contrib ant-optional

# Composer
RUN apt-get install -y curl git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
