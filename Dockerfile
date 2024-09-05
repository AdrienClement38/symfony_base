# Utiliser l'image officielle de PHP 8.2 avec FPM
FROM php:8.2-fpm

# Installer les dépendances requises
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Copy custom Xdebug configuration
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du dossier de travail
COPY . .

# Ajouter un script d'entrée
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exposer le port utilisé par PHP-FPM
EXPOSE 9000

# Utiliser le script d'entrée pour démarrer le conteneur
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]

