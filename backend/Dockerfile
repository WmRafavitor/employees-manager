FROM wyveo/nginx-php-fpm:php72

COPY . /usr/share/nginx
COPY ./.docker/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /usr/share/nginx

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
composer install --prefer-dist && \
cp .env.example .env && \
php artisan key:generate && \
php artisan jwt:secret && \
rm -rf html && \
chmod -R 777 /usr/share/nginx/storage/*