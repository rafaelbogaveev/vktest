FROM ubuntu:16.04

MAINTAINER Rafael Bogaveev <bogaveev.rafael@gmail.com>

RUN apt-get update && apt-get install php7.0 php7.0-fpm php7.0-mysql -y
RUN apt-get install php-pear -y
RUN apt-get install php7.0-dev -y
RUN apt-get install php-memcached -y


RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

RUN apt-get install nginx -y

ADD nginx.conf /etc/nginx
ADD http.conf /etc/nginx/conf.d

EXPOSE 80

ADD vktest /var/www/html

WORKDIR /var/www/html

RUN chmod 777 /var/www/html
RUN composer install

WORKDIR /var/www/html/src

CMD service nginx start && php -S localhost:8080

