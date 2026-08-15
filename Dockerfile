FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install \
    pdo_sqlite \
    pdo_pgsql \
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

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV DB_CONNECTION=pgsql
ENV SESSION_DRIVER=file
ENV CACHE_STORE=file

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache

RUN cat > /etc/apache2/sites-available/000-default.conf <<'EOF'
<VirtualHost *:80>

    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>
EOF

EXPOSE 80

CMD ["sh", "-c", "php artisan migrate --force && apache2-foreground"]