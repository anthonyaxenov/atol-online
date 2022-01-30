# АТОЛ Онлайн

Библиотека для фискализации чеков по 54-ФЗ через [облачные ККТ АТОЛ](https://online.atol.ru/).

[![GitHub Workflow Status (master)](https://img.shields.io/github/workflow/status/anthonyaxenov/atol-online/CI/master?logo=github)](https://github.com/anthonyaxenov/atol-online/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/anthonyaxenov/atol-online/branch/master/graph/badge.svg?token=WR2IV7FTF0)](https://codecov.io/gh/anthonyaxenov/atol-online)
[![Stable Version](https://img.shields.io/packagist/v/axenov/atol-online?label=stable)](https://packagist.org/packages/axenov/atol-online)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/axenov/atol-online?color=%23787cb4)](https://packagist.org/packages/axenov/atol-online)
[![License](https://img.shields.io/packagist/l/axenov/atol-online?color=%23369883)](LICENSE)
[![Liberapay](https://img.shields.io/liberapay/patrons/AnthonyAxenov.svg?logo=liberapay)](LICENSE)

**[Документация](/docs/readme.md)**

Текущие поддерживаемые версии АТОЛ Онлайн:

| Протокол | API | ФФД  | Статус         |
|----------|-----|------|----------------|
| v4       | 5.8 | 1.05 | Поддерживается |
| v5       | 2.0 | 1.2  | В планах       |

## Плюшечки

* Мониторинг ККТ и ФН
* Фискализация документов на облачной ККТ
* Валидация данных до отправки документа на ККТ (насколько это возможно, согласно схеме)
* Расчёты денег в копейках
* PSR-4 автозагрузка, покрытие настоящими тестами, fluent-setters, докблоки

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

1. Подключить пакет к проекту:
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

Для запуска тестов необходимо перейти в корень репозитория и выполнить одну из команд:

```bash
composer test # обычное тестирование
composer coverage # тестирование с покрытием
```

После тестирования с покрытием создаётся отчёт в директории `.coverage` в корне репозитория.

### Использование библиотеки

Весь исходный код находится в директории [`/src`](/src).
Вы имеете право использовать и распространять его на условиях **[лицензии MIT](LICENSE)**.

1. Обращайтесь к [документации библиотеки](/docs)
2. Обращайтесь к [исходному коду](/src) и описанным докблокам
3. Обращайтесь к [тестам](/tests/AtolOnline/Tests)
4. Используйте нормальную IDE

## Дополнительные ресурсы

* [Документация АТОЛ Онлайн](https://online.atol.ru/lib/)

[![Donate using Liberapay](https://liberapay.com/assets/widgets/donate.svg)](https://liberapay.com/AnthonyAxenov/donate)
