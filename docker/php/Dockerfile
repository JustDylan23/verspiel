FROM php:8.1-fpm-alpine

RUN apk add --update \
    make \
    curl \
    nodejs \
    yarn \
    git

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions mysqli pdo_mysql gd bcmath intl opcache apcu zip sysvsem

RUN wget https://getcomposer.org/installer -O - | php -- --install-dir=/usr/local/bin --filename=composer

ADD php.ini /usr/local/etc/php/php.ini
ADD php-fpm.conf /etc/php/php-fpm.d/

WORKDIR /var/www/symfony

EXPOSE 9000
EXPOSE 3000

CMD ["php-fpm", "-F"]
