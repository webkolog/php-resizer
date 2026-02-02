FROM php:8.2-cli

# Sistem bağımlılıklarını ve GD kütüphanesini kur
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Çalışma dizinini ayarla
WORKDIR /app

# Composer'ı kopyala (Testler için gerekli)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Proje dosyalarını kopyala
COPY . .

# Bağımlılıkları kur
RUN composer install