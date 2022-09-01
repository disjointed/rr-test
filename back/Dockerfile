FROM php:7.4-cli

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN apt-get update
RUN apt-get install -y git zip unzip libpng-dev libfreetype6-dev libjpeg62-turbo-dev libzip-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql gd zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN adduser --uid 1000 --gecos '' --disabled-password myuser
RUN git config --global user.email "test@rr.org"
RUN git config --global user.name "rr-test"
RUN apt-get autoremove -y
RUN rm -rf /var/lib/apt/lists/*
RUN php artisan init-db

USER myuser

WORKDIR /home/myuser/app

CMD php artisan serve --host=0.0.0.0 --port=8000

EXPOSE 8000
