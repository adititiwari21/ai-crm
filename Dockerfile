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
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install \
    --no-dev \
    --prefer-source \
    --optimize-autoloader \
    --no-interaction

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/html/database/database.sqlite
ENV SESSION_DRIVER=file
ENV CACHE_STORE=file
ENV APP_URL=https://ai-crm-nowm.onrender.com
ENV ASSET_URL=https://ai-crm-nowm.onrender.com

RUN mkdir -p database storage/framework/views storage/framework/sessions storage/framework/cache bootstrap/cache \
    && touch database/database.sqlite \
    && chown -R www-data:www-data database storage bootstrap/cache \
    && chmod -R 777 database storage bootstrap/cache

RUN cat > /etc/apache2/sites-available/000-default.conf <<'EOF'
<VirtualHost *:80>

    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
    </Directory>

    SetEnvIf X-Forwarded-Proto "^https$" HTTPS=on

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>
EOF

EXPOSE 80

CMD ["sh", "-c", "touch /var/www/html/database/database.sqlite && chown -R www-data:www-data /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache && chmod -R 777 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache && php artisan migrate --force && apache2-foreground"]