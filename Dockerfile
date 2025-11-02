# ========= BASE PHP IMAGE =========
FROM php:8.2-fpm


# --- System dependencies ---
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libonig-dev libpng-dev nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring zip

# --- Install Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# --- Copy all project files ---
COPY . .

# --- Install PHP deps ---
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# --- Install Node (use Debian’s Node 20 or install via nvm if older) ---
RUN npm install -g npm@latest

# --- Default port for php-fpm ---
EXPOSE 9000
CMD ["php-fpm"]
