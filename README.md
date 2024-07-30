# Проект "Goods"

## Описание

Этот проект включает клиентскую часть на Vue, серверную часть на Laravel и базу данных MySQL.

## Структура проекта

- `Goods-Test/` — Серверная часть на Laravel
- `goods-front/` — Клиентская часть на Vue

## Требования

- Node.js и npm
- PHP и Composer
- MySQL

## Установка и запуск

### 1. Серверная часть (Laravel)

1. Перейдите в каталог `Goods-Test`:

    ```bash
    cd Goods-Test
    ```

2. Установите зависимости:

    ```bash
    composer install
    ```

3. Скопируйте файл окружения и настройте параметры подключения к базе данных:

    ```bash
    cp .env.example .env
    ```

    Отредактируйте файл `.env`, чтобы указать параметры базы данных, например:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=goods_db
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

4. Сгенерируйте ключ приложения:

    ```bash
    php artisan key:generate
    ```

5. Запустите миграции для создания таблиц в базе данных:

    ```bash
    php artisan migrate
    ```

6. Запустите сервер:

    ```bash
    php artisan serve
    ```

    Сервер будет доступен по адресу [http://localhost:8000](http://localhost:8000).

7. Запустите обработчик очередей:

    В проекте используется очередь для асинхронной обработки задач. Запустите команду для обработки очередей:

    ```bash
    php artisan queue:work
    ```

### 2. Клиентская часть (Vue)

1. Перейдите в каталог `goods-front`:

    ```bash
    cd goods-front
    ```

2. Установите зависимости:

    ```bash
    npm install
    ```

3. Для разработки:

    ```bash
    npm run serve
    ```

    Приложение будет доступно по адресу [http://localhost:8080](http://localhost:8080).

4. Для сборки проекта для продакшена:

    ```bash
    npm run build
    ```

## Использование API

Вы можете получить доступ к документации API (Swagger) по адресу [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation).

## Разработка

1. Внесите изменения в код в соответствующих каталогах (`Goods-Test/`, `goods-front/`).
2. Перезапустите сервисы, если это необходимо.
