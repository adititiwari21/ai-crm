FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install \
    pdo_sqlite \
    zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# Create SQLite database for the deployed app
RUN touch database/database.sqlite

# Use SQLite and file-based sessions/cache
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/html/database/database.sqlite
ENV SESSION_DRIVER=file
ENV CACHE_STORE=file

# Run all Laravel migrations during image build
RUN php artisan migrate --force

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    database

RUN chmod -R 775 \
    storage \
    bootstrap/cache \
    database

RUN sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' \
    /etc/apache2/sites-available/000-default.conf

RUN sed -i 's#<Directory /var/www/>#<Directory /var/www/html/public>#' \
    /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]