# АТОЛ Онлайн

[![Latest Stable Version](http://poser.pugx.org/axenov/atol-online/v)](https://packagist.org/packages/axenov/atol-online)
[![Latest Unstable Version](http://poser.pugx.org/axenov/atol-online/v/unstable)](https://packagist.org/packages/axenov/atol-online)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/axenov/atol-online)](https://packagist.org/packages/axenov/atol-online)
[![Total Downloads](https://img.shields.io/packagist/dt/axenov/atol-online)](https://packagist.org/packages/axenov/atol-online)
[![License](https://img.shields.io/packagist/l/axenov/atol-online?color=%23369883)](LICENSE)

Библиотека для фискализации чеков по 54-ФЗ через [облачную ККТ АТОЛ](https://online.atol.ru/).

**[Документация](/docs/readme.md)**

---

**В ветке `dev` проводится глубокий рефакторинг, стабилизация и активная подготовка к `v1.0.0`.
Документация актуализируется постепенно.**

---

| master | [![GitHub Workflow Status (master)](https://img.shields.io/github/workflow/status/anthonyaxenov/atol-online/CI/master?logo=github)](https://github.com/anthonyaxenov/atol-online/actions/workflows/ci.yml) | [![codecov](https://codecov.io/gh/anthonyaxenov/atol-online/branch/master/graph/badge.svg?token=WR2IV7FTF0)](https://codecov.io/gh/anthonyaxenov/atol-online)  |
|--------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------|
| dev    | [![GitHub Workflow Status (dev)](https://img.shields.io/github/workflow/status/anthonyaxenov/atol-online/CI/dev?logo=github)](https://github.com/anthonyaxenov/atol-online/actions/workflows/ci.yml)       | [![codecov dev](https://codecov.io/gh/anthonyaxenov/atol-online/branch/dev/graph/badge.svg?token=WR2IV7FTF0)](https://codecov.io/gh/anthonyaxenov/atol-online) |

Текущие поддерживаемые версии АТОЛ Онлайн:

| Протокол | API | ФФД  | Статус      |
|----------|-----|------|-------------|
| v4       | 5.8 | 1.05 | Рефакторинг |
| v5       | 2.0 | 1.2  | В планах    |

## Плюшечки

* Мониторинг ККТ и ФН
* Фискализация докумнетов на облачной ККТ
* Валидация данных до отправки документа на ККТ (насколько это возможно, согласно схеме)
* Расчёты денег в копейках
* PSR-4 автозагрузка
<!--* Фактически полное покрытие тестами-->

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

Для запуска тестов необходимо перейти в корень репозитория и выполнить одну из команд:

```bash
composer test # обычное тестирование
composer test-cov # тестирование с покрытием
```

После тестирования с покрытием создаётся отчёт в директории `.coverage-report` в корне репозитория.

## Использование библиотеки

Весь исходный код находится в директории [`/src`](/src).

**Комментарии phpdoc есть буквально везде. Прокомментировано вообще всё.**

1. Обращайтесь к [документации библиотеки](/docs).
2. Обращайтесь к [исходному коду](/src).
3. Обращайтесь к [тестам](/tests).
4. Используйте подсказки вашей IDE.

Тогда у вас не возникнет затруднений.

## Дополнительные ресурсы

* **[Документация к библиотеке](/docs/readme.md)**
* Telegram-канал: [@atolonline_php](https://t.me/atolonline_php)
* [Документация АТОЛ Онлайн](https://online.atol.ru/lib/)

## Лицензия

Вы имеете право использовать и распространят код из этого репозитория на условиях **[лицензии MIT](LICENSE)**.
