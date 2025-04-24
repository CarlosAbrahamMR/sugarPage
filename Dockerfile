FROM php:8.2-fpm

# Instala dependencias del sistema y PHP
RUN apt-get update && apt-get install -y \
    git curl zip unzip libonig-dev libxml2-dev libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev ca-certificates \
    build-essential \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath gd

# Instala Node.js 14 manualmente (Laravel Mix)
RUN curl -fsSL https://nodejs.org/dist/v14.21.3/node-v14.21.3-linux-x64.tar.xz -o node.tar.xz \
    && tar -xf node.tar.xz \
    && mv node-v14.21.3-linux-x64 /usr/local/node \
    && ln -s /usr/local/node/bin/node /usr/local/bin/node \
    && ln -s /usr/local/node/bin/npm /usr/local/bin/npm \
    && ln -s /usr/local/node/bin/npx /usr/local/bin/npx

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www

COPY . .

RUN mkdir -p storage/framework/views storage/framework/cache storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# 👉 Aquí está el fix
RUN composer require laravel/ui \
    && composer install --no-dev \
    && npm install --legacy-peer-deps \
    && php artisan key:generate \
    && php artisan optimize:clear \
    && php artisan migrate --seed \
    && npm run dev

EXPOSE 9000

CMD ["php-fpm"]
