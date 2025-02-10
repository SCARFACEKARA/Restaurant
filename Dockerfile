FROM php:8.2-fpm

# Installer les dépendances système et extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql mbstring \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail
WORKDIR /app

# Copier uniquement composer.json et composer.lock pour optimiser le cache Docker
COPY composer.json composer.lock /app/

# Installer les dépendances PHP avant de copier tout le code source
RUN composer install --no-dev --optimize-autoloader

# Copier le reste du projet
COPY . /app

# Exposer le bon port
EXPOSE 9000

# Démarrer PHP-FPM
CMD ["php-fpm"]
