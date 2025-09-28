#!/bin/bash

echo "Initializing Laravel application..."

# Ожидание запуска MySQL
echo "Waiting for MySQL to start..."
while ! mysqladmin ping -h"mysql" -u"root" -p"rootpassword123" --silent; do
    sleep 1
done

echo "MySQL started!"

# Переход в директорию проекта
cd /var/www/html

# Установка зависимостей
if [ ! -f "vendor/autoload.php" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-progress --no-interaction
fi

# Создание файла .env если его нет
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Генерация ключа приложения
echo "Generating application key..."
php artisan key:generate

# Запуск миграций и сидов
echo "Running migrations and seeds..."
php artisan migrate --force
php artisan db:seed --force

echo "Initialization completed!"