# Мониторинг ККТ

[Вернуться к содержанию](readme.md)

---

Библиотека предоставляет возможность следить за состоянием ваших облачных ККТ через API.

## Инициализация

Для этого следует использовать класс `KktMonitor`:

```php
// можно передать параметры подключения в конструктор
$monitor = new AtolOnline\Api\KktMonitor(
    login: 'mylogin',
    password: 'qwerty'
);

// можно - отдельными сеттерами
$monitor = new AtolOnline\Api\KktMonitor();
    ->setLogin($credentials['login'])
    ->setPassword($credentials['password']);
```

Логин и пароль для мониторинга те же, что для регистрации документов.

**По умолчанию монитор работает в тестовом режиме.**
Перевести его в боевой режим можно:

```php
// передачей в конструктор `false` первым параметром:
$monitor = new AtolOnline\Api\KktMonitor(false, /*...*/);

// или отдельным сеттером
$monitor->setTestMode(false);
```

**Тестовый режим** нужен для проверки работоспособности библиотеки и API АТОЛ.

**В боевом режиме** можно получать данные по своим ККТ.

## Получение данных обо всех своих ККТ

Для получения данных обо всех своих ККТ следует вызвать метод `AtolOnline\Api\KktMonitor::getAll()`:

```php
$kkts = $monitor->getAll();
```

В ответе будет итерируемая коллекция объектов `AtolOnline\Entities\Kkt`. Каждый из этих объектов содержит атрибуты:

```php
// для примера получим первую ККТ из всех
$kkt = $kkts->first();

// посмотрим на её атрибуты:
$kkt->serialNumber; // Заводской номер ККТ
$kkt->registrationNumber; // Регистрационный номер машины (РНМ)
$kkt->deviceNumber; // Номер автоматического устройства (внутренний идентификатор устройства)
$kkt->fiscalizationDate; // Дата активации (фискализации) ФН с указанием таймзоны
$kkt->fiscalStorageExpiration; // Дата замены ФН (Срок действия ФН), с указанием таймзоны
$kkt->signedDocuments; // Количество подписанных документов в ФН
$kkt->fiscalStoragePercentageUse; // Наполненость ФН в %
$kkt->fiscalStorageINN; // ИНН компании (указанный в ФН)
$kkt->fiscalStorageSerialNumber; // Заводской (серийный) номер ФН
$kkt->fiscalStoragePaymentAddress; // Адрес расчёта, указанный в ФН
$kkt->groupCode; // Код группы кассы
$kkt->timestamp; // Время и дата формирования данных, UTC
$kkt->isShiftOpened; // Признак открыта смена (true) или закрыта (false)
$kkt->shiftNumber; // Номер смены (или "Номер закрытой смены", когда смена закрыта)
$kkt->shiftReceipt; // Номер документа за смену (или "Кол-во чеков закрытой смены", когда смена закрыта)
$kkt->unsentDocs; // Количество неотправленных документов. Указывается, если значение отлично от 0.
$kkt->firstUnsetDocTimestamp; // Дата первого неотправленного документа. Указывается, если есть неотправленные документы.
$kkt->networkErrorCode; // Код ошибки сети
```

Эти поля описаны в документации мониторинга на [стр. 11](https://online.atol.ru/files/API_service_information.pdf)

Сопоставления кодов ошибок и их описаний доступны в массиве `AtolOnline\Entities\Kkt::ERROR_CODES`.

## Получение данных об одной из своих ККТ

Для этого следует вызвать метод `AtolOnline\Api\KktMonitor::getOne()`, передав на вход серийный номер (`serialNumber`)
нужной ККТ:

```php
$kkt = $monitor->getOne($kkts->first()->serialNumber);
```

Метод вернёт единственный объект `AtolOnline\Entities\Kkt` с атрибутами, описанными выше.

## Получение последнего ответа от сервера

Класс `AtolOnline\Api\KktMonitor` расширяет абстрактный класс `AtolOnline\Api\AtolClient`.

Это значит, что последний ответ от API АТОЛ всегда сохраняется объектом класса `AtolOnline\Api\KktResponse`. К нему
можно обратиться через метод `AtolOnline\Api\KktMonitor::getResponse()`, независимо от того, что возвращают другие
методы монитора.

---

[Вернуться к содержанию](readme.md)