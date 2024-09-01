# Définir la version de PHP utilisée comme base pour l'image Docker
FROM php:8.3

# Mettre à jour la liste des paquets disponibles et installer les dépendances nécessaires
RUN apt-get update -y && apt-get install -y \
    # Installez OpenSSL pour la gestion des certificats SSL/TLS
    openssl \
    # Installez les outils de compression et de décompression ZIP
    zip \
    unzip \
    # Installez Git pour la gestion des versions
    git \
    # Installez les bibliothèques de développement nécessaires pour les extensions PHP
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    # Installez le client MariaDB pour l'accès à la base de données
    mariadb-client \
    # Installez les extensions PHP nécessaires
    && docker-php-ext-install pdo_mysql mbstring

# Télécharger et installer Composer, le gestionnaire de dépendances PHP
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail pour l'application
WORKDIR /app

# Copier les fichiers de l'application dans le répertoire de travail
COPY . /app

# Changer les droits d'accès pour le répertoire de travail
RUN chown -R www-data:www-data /app

# Installer les dépendances de l'application avec Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --verbose

# Installer la bibliothèque JWT-Auth pour l'authentification
RUN composer require php-open-source-saver/jwt-auth

# Créer un lien symbolique pour le stockage de l'application
RUN php artisan storage:link

# Exécuter les commandes de configuration et de démarrage de l'application
CMD php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider" && \
    php artisan key:generate && \
    php artisan migrate:fresh && \
    php artisan db:seed && \
    php artisan jwt:secret && \
    php artisan serve --host=0.0.0.0 --port=8181

# Exposer le port 8181 pour l'accès à l'application
EXPOSE 8181
