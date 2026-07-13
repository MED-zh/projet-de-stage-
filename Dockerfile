FROM eworkssk/php-fpm:8.3

USER root

WORKDIR /var/www

# Dépendances système
RUN apt-get update && apt-get install -y --fix-missing \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    unzip \
    git \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Extensions PHP pour Laravel
RUN docker-php-ext-install mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier composer.json en premier (cache layer)
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-interaction

# Copier le reste de l'application
COPY . .

# Finaliser autoloader
RUN composer dump-autoload --optimize

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Configuration php-fpm pool (fixes "user has not been defined" error)
COPY www.conf /usr/local/etc/php-fpm.d/www.conf

# Exposer le port php-fpm et lancer le service
EXPOSE 9000
CMD ["php-fpm"]