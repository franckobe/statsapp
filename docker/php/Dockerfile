FROM php:8.3-fpm

LABEL maintainer="Franck GARROS"
# Met à jour les paquets et installe les dépendances nécessaires pour GD
RUN apt-get update && apt-get upgrade
# Installe les dépendances nécessaires pour Composer et Symfony
RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev poppler-utils libpq-dev \
    && docker-php-ext-install zip pdo pdo_pgsql pgsql

WORKDIR /var/www

# Installer Node.js et npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install Composer \
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader
RUN npm build

ENV COMPOSER_ALLOW_SUPERUSER=1

# Exécute la commande de création de projet uniquement si le répertoire est vide
RUN echo "Current directory:" && pwd && echo "Contents:" && ls -la

CMD ["php-fpm"]
EXPOSE 9000
