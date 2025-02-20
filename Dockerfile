# Utiliser une image PHP officielle
FROM php:8.1-apache
 
# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip
 
# Effacer le cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
 
# Installer les extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
 
# Obtenir la dernière version de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
 
# Définir le répertoire de travail
WORKDIR /var/www/html
 
# Copier les fichiers de l'application
COPY . .
 
# Installer les dépendances
RUN composer install --no-interaction --no-dev --optimize-autoloader
 
# Copier la configuration Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
 
# Activer le module mod_rewrite d'Apache
RUN a2enmod rewrite
 
# Changer la propriété de nos applications
RUN chown -R www-data:www-data /var/www/html
 
# Exposer le port 80
EXPOSE 80
 
# Démarrer Apache
CMD ["apache2-foreground"]