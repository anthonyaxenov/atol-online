# АТОЛ Онлайн

---

**В этой ветке проводится глубокий рефакторинг и активная подготовка к `v1.0.0`.**

**Актуальность документации -- околонулевая.**

**Общая работоспособность -- нулевая.**

---

[![Master build](https://github.com/anthonyaxenov/atol-online/actions/workflows/master.yml/badge.svg)](https://github.com/anthonyaxenov/atol-online/actions/workflows/master.yml)
[![Dev build](https://github.com/anthonyaxenov/atol-online/actions/workflows/dev.yml/badge.svg)](https://github.com/anthonyaxenov/atol-online/actions/workflows/dev.yml)

Библиотека для фискализации чеков по 54-ФЗ через [облачную ККТ АТОЛ](https://online.atol.ru/).

Текущая поддерживаемая версия API: **5.1**

**[Документация](/docs/readme.md)**

## Системные требования

* PHP 7.4+
* composer
* php-json

## Начало работы

### Подключение библиотеки

1. Подключить пакет к вашему проекту:  
   ```bash
   composer require axenov/atol-online
   ```
2. В нужном месте проекта объявить параметры, используя константы, и подключить **composer**, если это не сделано ранее:  
   ```php
   require($project_root.'/vendor/autoload.php');
   ```
   где `$project_root` — абсолютный путь к корневой директории вашего проекта.

### Тестирование кода библиотеки

Файлы тестов находятся в директории `/tests` корня репозитория.

Для запуска тестов необходимо перейти в корень вашего проекта и выполнить команду:

```bash
./vendor/bin/phpunit
```

## Настройка ККТ

Для работы с облачной ККТ необходимы следующие параметры:
* логин;
* пароль;
* код группы.

Чтоб получить их, нужно:
1. авторизоваться на [online.atol.ru](https://online.atol.ru/lk/Account/Login);
2. на странице [Мои компании](https://online.atol.ru/lk/Company/List) нажать кнопку **Настройки интегратора**.  
   Скачается XML-файл с нужными настройками.

Также для работы потребуются:
* ИНН продавца;
* URL места расчёта (ссылка на ваш интернет-сервис).

## Использование библиотеки

### Доступные методы и классы

Весь исходный код находится в директории [`/src`](/src).

**Комментарии phpDoc есть буквально к каждому классу, методу или полю.
Прокомментировано вообще всё.**

1. Обращайтесь к [документации](/docs).
2. Обращайтесь к [исходному коду](/src).
3. Обращайтесь к [тестам](/test).
4. **Используйте подсказки вашей IDE.**

Тогда у вас не возникнет затруднений.

Для тех, кто решил подробно разобраться в работе библиотеки, отдельно отмечу нюансы, которые могут ускользнуть от внимания:
1. Класс `AtolOnline\Api\Kkt` унаследован от `GuzzleHttp\Client` со всеми вытекающими;
2. Все классы, унаследованные от `AtolOnline\Entities\AtolEntity` приводятся к JSON-строке.

### Общий алгоритм

1. Задать настройки ККТ
2. Собрать данные о покупателе
3. Собрать данные о продавце
4. Собрать данные о предметах расчёта (товары, услуги и пр.)
5. Создать документ, добавив в него покупателя, продавца и предметы расчёта
6. Отправить документ на регистрацию:  
    6.1. *Необязательно:* задать `callback_url`, на который АТОЛ отправит HTTP POST о состоянии документа.
7. Запомнить `uuid` из пришедшего ответа, поскольку он пригодится для последующей проверки статуса фискализации.
    > Если с документом был передан `callback_url`, то ответ придёт на этот самый URL.  
    Если с документом **НЕ** был передан `callback_url` **либо** callback от АТОЛа не пришёл в течение 300 секунд (5 минут), нужно запрашивать вручную по `uuid`, пришедшему от АТОЛа в ответ на регистрацию документа.
8. Проверить состояние документа (нет необходимости, если передавался `callback_url`):  
    8.1. взять `uuid` ответа, полученного на запрос фискализации;  
    8.2. отправить его в запросе состояния документа.
    > Данные о документе можно получить только в течение 32 суток после успешной фискализации.

## Об отправке электронного чека покупателю

После успешной фискализации документа покупатель автоматически получит уведомление **от ОФД**, который используется в связке с вашей ККТ:
* **по email**, если в документе указан email клиента;
* **по смс**:
    * если в документе указан номер телефона клиента;
    * если на стороне ОФД необходима и подключена услуга СМС-информирования (уточняйте подробности о своего ОФД).

> Если заданы email и телефон, то отдаётся приоритет email.

## Дополнительные ресурсы

Функционал, находящийся в разработке: [ROADMAP.md](ROADMAP.md)

Официальные ресурсы АТОЛ Онлайн:
* **[Вся документация](https://online.atol.ru/lib/)**
* Telegram-канал: [@atolonline](https://t.me/atolonline)

## Лицензия

Вы имеете право использовать код из этого репозитория только на условиях **[лицензии MIT](LICENSE)**.
