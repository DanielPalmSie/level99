FROM php:8.1-fpm

# Установка необходимых библиотек и расширений
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql zip

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установка MySQL клиента
RUN apt-get update && apt-get install -y default-mysql-client

# Создание директории для приложения
WORKDIR /var/www/html

# Копирование файлов проекта
COPY . .

# Назначение прав на директорию с приложением
RUN chown -R www-data:www-data /var/www/html

# Публикация порта 9000 для PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]

# Добавление bash для возможности входа в контейнер
RUN apt-get update && apt-get install -y bash
