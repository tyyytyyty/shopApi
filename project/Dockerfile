FROM php:8.2-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client

# Очистка кеша
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка PHP расширений
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Создание пользователя для приложения
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Установка рабочей директории
WORKDIR /var/www/html

# Копирование существующих прав доступа из каталога приложения
COPY --chown=www:www . /var/www/html

# Переключение на пользователя www
USER www

# Экспорт порта и запуск php-fpm сервера
EXPOSE 9000
CMD ["php-fpm"]
