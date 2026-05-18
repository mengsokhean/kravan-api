FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl zip unzip git libpng-dev libonig-dev \
    libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE $PORT

CMD php artisan serve --host=0.0.0.0 --port=$PORT