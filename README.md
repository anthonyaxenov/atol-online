# АТОЛ Онлайн

---

**В этой ветке проводится глубокий рефакторинг, стабилизация и активная подготовка к `v1.0.0`. Документация
актуализируется постепенно.**

---

[![Master build](https://github.com/anthonyaxenov/atol-online/actions/workflows/master.yml/badge.svg)](https://github.com/anthonyaxenov/atol-online/actions/workflows/master.yml)
[![Dev build](https://github.com/anthonyaxenov/atol-online/actions/workflows/dev.yml/badge.svg)](https://github.com/anthonyaxenov/atol-online/actions/workflows/dev.yml)
[![Latest Stable Version](http://poser.pugx.org/axenov/atol-online/v)](https://packagist.org/packages/axenov/atol-online) 
[![Latest Unstable Version](http://poser.pugx.org/axenov/atol-online/v/unstable)](https://packagist.org/packages/axenov/atol-online)
[![Total Downloads](http://poser.pugx.org/axenov/atol-online/downloads)](https://packagist.org/packages/axenov/atol-online)
[![License](http://poser.pugx.org/axenov/atol-online/license)](https://packagist.org/packages/axenov/atol-online)

Библиотека для фискализации чеков по 54-ФЗ через [облачную ККТ АТОЛ](https://online.atol.ru/).

**[Документация](/docs/readme.md)**

Текущие поддерживаемые версии АТОЛ Онлайн:

| Протокол | API | ФФД  | Статус      |
|----------|-----|------|-------------|
| v4       | 5.7 | 1.05 | Рефакторинг |
| v5       | 2.0 | 1.2  | В планах    |

## Плюшечки

* Мониторинг ККТ и ФН
* Фискализация докумнетов на облачной ККТ
* Валидация данных до отправки документа на ККТ (насколько это возможно, согласно схеме)
* Расчёты денег в копейках
* Фактически полное покрытие тестами
* PSR-4 автозагрузка

## Системные требования

* php8.0+
* [composer](https://getcomposer.org/)
* расширения php (скорее всего, устанавливать их отдельно не придётся):
    * `php-json`
    * `php-curl`
    * `php-mbstring`
    * `php-tokenizer`

## Начало работы

### Подключение библиотеки

1. Установить библиотеку пакет к проекту:
   ```bash
   composer require axenov/atol-online
   ```
2. В нужном месте проекта подключить автозагрузчик composer-зависимостей, если это не сделано ранее:
   ```php
   require($project_root . '/vendor/autoload.php');
   ```
   где `$project_root` — абсолютный путь к корневой директории вашего проекта.
   > При использовании фреймворков это обычно не требуется.

### Тестирование кода библиотеки

Файлы тестов находятся в директории `/tests` корня репозитория.

Для запуска тестов необходимо перейти в корень вашего проекта и выполнить команду:

```bash
composer test
```

## Использование библиотеки

Весь исходный код находится в директории [`/src`](/src).

**Комментарии phpdoc есть буквально везде. Прокомментировано вообще всё.**

1. Обращайтесь к [документации библиотеки](/docs).
2. Обращайтесь к [исходному коду](/src).
3. Обращайтесь к [тестам](/tests).
4. Используйте подсказки вашей IDE.

Тогда у вас не возникнет затруднений.

## Дополнительные ресурсы

* [Документация АТОЛ](https://online.atol.ru/lib/)
  **[Документация к библиотеке](/docs/readme.md)**
* Telegram-канал: [@atolonline_php](https://t.me/atolonline_php)
* Функционал, находящийся в разработке: [ROADMAP.md](ROADMAP.md)

## Лицензия

Вы имеете право использовать и распространят код из этого репозитория на условиях **[лицензии MIT](LICENSE)**.
