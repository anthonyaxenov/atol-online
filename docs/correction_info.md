# Работа с данными коррекции

Объект для данных коррекции инициализируется следующим образом:

```php
$info = new AtolOnline\Entities\CorrectionInfo();
```

У объекта должны быть указаны все следующие обязательные атрибуты:
* тип коррекции (тег ФФД 1173) - все типы перечислены в классе `AtolOnline\Constants\CorrectionTypes`;
* дата документа основания для коррекции в формате `d.m.Y` (тег ФФД 1178);
* номер документа основания для коррекции (тег ФФД 1179);
* описание коррекции (тег ФФД 1177).

Указать эти атрибуты можно двумя способами:

```php
use AtolOnline\{Entities\CorrectionInfo, Constants\CorrectionTypes};

// 1 способ - через конструктор
$info = new CorrectionInfo(
    CorrectionTypes::SELF, // тип коррекции
    '01.01.2019', // дата документа коррекции
    '12345', // номер документа коррекции
    'test' // описание коррекции
);

// 2 способ - через сеттеры
$info = (new CorrectionInfo())
    ->setType(CorrectionTypes::INSTRUCTION)
    ->setDate('01.01.2019')
    ->setName('test')
    ->setNumber('9999');

// либо комбинация этих способов
```

Получить установленные значения атрибутов можно через геттеры:

```php
$info->getType();
$info->getDate();
$info->getName();
$info->getNumber();
```

Объект класса приводится к JSON-строке автоматически или принудительным приведением к `string`:

```php
echo $customer;
$json_string = (string)$customer;
```

Чтобы получить те же данные в виде массива, нужно вызвать метод `jsonSerialize()`:

```php
$json_array = $customer->jsonSerialize();
```







