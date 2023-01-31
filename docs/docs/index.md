# Php Test

Тестовое задание по PHP

По задаче требовалось и собрать микро-фреймворк, и реализовать логику работы API.
Поэтому, по итогу, получилось реализовать лишь по минимуму каждую из этих подзадач. Т.к. собрать микро-фреймворк -- тоже чего-то стоит. Собственно, почему обычно и используют готовые решения на эту тему :-)

## Использовались версии:

    Php: 8.1.12
    Mysql: 10.4.27-MariaDB

## Дампы БД:

    dump.sql # Структура БД
    tests/dump.sql # Структура БД для тестов. По факту, дампы одинаковые.

## .env файлы:

    .env # Основной
    tests/.env # Для тестов

## Установка:

* `composer install`
* `composer dump-autoload -o` -- Скорее всего не потребуется. Это на случай, если не будут находится локальные файлы с кодом проекта.

## Вариант запуска:

* `php -S localhost:8001`

## Генерация Auth-Token:

* `./cli.php auth-token --type=<TYPE>` -- Где TYPE: CUSTOMER | OWNER | COURIER
* `./cli.php auth-token --type=CUSTOMER` -- Пример.


## Примеры запросов с использованием утилиты HTTPIE:

* `echo '{"customer_address": {"street": "Abcd", "house_number": "1", "flat_number": "1", "entrance": "1", "floor": "1"}}' | http POST 'http://localhost:8001/order/calc' Auth-Token:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyVHlwZSI6IkNVU1RPTUVSIn0.QZQN782gmSp-MgT1VRTMRpO4jXemVn02xun2mFOkCvU`
* `echo '{"order": {"products": [{"id":13,"count":17}]}}' | http POST 'http://localhost:8001/order/create' Auth-Token:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyVHlwZSI6IkNVU1RPTUVSIn0.QZQN782gmSp-MgT1VRTMRpO4jXemVn02xun2mFOkCvU`
* `http GET 'http://localhost:8001/order/list' Auth-Token:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyVHlwZSI6Ik9XTkVSIn0.bRhGwPg3kNRvksx0SXWLl8Y9OOzjZ_CYH8dA9FLtKAk`
* `http GET 'http://localhost:8001/order/view/1' Auth-Token:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyVHlwZSI6IkNPVVJJRVIifQ.Xsg4HM1LV0QwMzT7RugKIMT0z_gPVeYHAtdtiexVDdk`


## Запуск тестов:

* `./vendor/phpunit/phpunit/phpunit --bootstrap ./autoload.php tests`
