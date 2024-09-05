#!/bin/sh

# Installer les dépendances Symfony
composer install --no-scripts --no-interaction

# Démarrer PHP-FPM
php-fpm
