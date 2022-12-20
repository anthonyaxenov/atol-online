# АТОЛ Онлайн

Библиотека для фискализации чеков по 54-ФЗ через [облачные ККТ АТОЛ](https://online.atol.ru/).

[![GitHub Workflow Status (master)](https://img.shields.io/github/actions/workflow/status/anthonyaxenov/atol-online/ci.yml?branch=master&logo=github)](https://github.com/anthonyaxenov/atol-online/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/anthonyaxenov/atol-online/branch/master/graph/badge.svg?token=WR2IV7FTF0)](https://codecov.io/gh/anthonyaxenov/atol-online)
[![Stable Version](https://img.shields.io/packagist/v/axenov/atol-online?label=stable)](https://packagist.org/packages/axenov/atol-online)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/axenov/atol-online?color=%23787cb4)](https://packagist.org/packages/axenov/atol-online)
[![License](https://img.shields.io/packagist/l/axenov/atol-online?color=%23369883)](LICENSE)
[![buymeacoffee](https://img.shields.io/badge/-buy_me_a%C2%A0coffee-gray?logo=buy-me-a-coffee)](https://www.buymeacoffee.com/axenov)

**[Документация](/docs/readme.md)**

Текущие поддерживаемые версии АТОЛ Онлайн:

| Протокол | API  | ФФД  | Статус         |
|----------|------|------|----------------|
| v4       | 5.10 | 1.05 | Поддерживается |
| v5       | 3.0  | 1.2  | В планах       |

Поддерживаемые возможности:

* Мониторинг ККТ и ФН
* Фискализация документов на облачной ККТ
* Валидация данных до отправки документа на ККТ (насколько это возможно, согласно схеме)
* Расчёты денег в копейках
* PSR-4 автозагрузка, покрытие настоящими тестами, fluent-setters, докблоки

## Системные требования

* `php v8.1` и выше
* `php-json`
* `php-mbstring`
* [composer](https://getcomposer.org/)

> Для использования на php8.0 используйте версии библиотеки до 1.0.2 включительно.

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

### Использование библиотеки

Вы имеете право использовать и распространять код на условиях **[лицензии MIT](LICENSE)**.

Дополнительная информация может быть найдена здесь:

1. [Документации к библиотеке](/docs)
2. [Документация АТОЛ Онлайн](https://online.atol.ru/lib/)
3. [Исходный код](/src), докблоки
4. [Тесты](/tests/AtolOnline/Tests)

### Тестирование кода библиотеки

Файлы тестов находятся в директории `/tests` корня репозитория.

Для запуска тестов необходимо перейти в корень репозитория и выполнить одну из команд:

```bash
composer psalm    # статический анализ
composer phpcs    # синтаксический анализ
composer test     # полное тестирование без покрытия
composer coverage # полное тестирование с покрытием
```

После тестирования с покрытием в корне репозитория создаётся отчёт, который сохраняется в директории `.coverage`.
Для тестирования с покрытием необходим `php-xdebug` с параметром `xdebug.mode = coverage,...`.
