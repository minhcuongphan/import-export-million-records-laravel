FROM php:8-fpm

RUN apt-get update && apt-get install -y \
    cron \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libzip-dev \
    libmcrypt-dev \
    libonig-dev \
    zlib1g-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    graphviz \
    curl \
    supervisor

RUN apt-get update
RUN apt-get install -y libcurl4-openssl-dev
RUN apt-get install -y pkg-config
RUN apt-get install -y libssl-dev
RUN apt-get install -y libmagickwand-dev --no-install-recommends
RUN docker-php-ext-install -j$(nproc) iconv
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install imagick
RUN docker-php-ext-enable imagick
RUN docker-php-ext-install sockets
RUN docker-php-ext-install pcntl
RUN pecl install mongodb && docker-php-ext-enable mongodb

COPY cron.txt /etc/cron.d/web
RUN chmod 0644 /etc/cron.d/web
RUN crontab /etc/cron.d/web

#xdebug install
#RUN pecl install xdebug-2.5.0 && docker-php-ext-enable xdebug
#RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
#RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
#RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
#RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;

RUN docker-php-ext-install pdo_mysql pdo mysqli zip
RUN chown -R www-data:www-data /var/www
RUN chmod -R 777 /var/www
RUN usermod -a -G www-data root

# Install composer
ENV COMPOSER_HOME /composer
ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
RUN composer self-update

RUN apt-get install -y gnupg2
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install -y nodejs

RUN npm install pm2 -g
RUN cp /usr/share/zoneinfo/Asia/Ho_Chi_Minh /etc/localtime
RUN apt-get install redis-tools -y
RUN composer global require "phpstan/phpstan:^1.1.1"
RUN chmod +x /composer/vendor/bin/phpstan

COPY *.sh /scripts/
RUN chmod a+x /scripts/*.sh

CMD ["sh", "-c", "php-fpm"]
#CMD ["/scripts/script.sh"]
#CMD [ "pm2-runtime", "start","/var/www/html/queue.example.yml"]
