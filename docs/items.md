# Работа с предметами расчёта

[Вернуться к содержанию](readme.md)

---

## Один объект

Объект предмета расчёта инициализируется следующим образом:

```php
$vat = new AtolOnline\Entities\Item();
```

У объекта предмета расчёта должны быть указаны все следующие обязательные атрибуты:
* наименование (тег ФФД - 1030);
* цена (тег ФФД - 1079);
* количество, вес (тег ФФД - 1023).

У объекта предмета расчёта также могут быть указаны следующие необязательные атрибуты:
* единица измерения количества (тег ФФД - 1197);
* признак способа оплаты (тег ФФД - 1214) - перечислены в классе `AtolOnline\Constants\PaymentMethods`;
* признак предмета расчёта (тег ФФД - 1212) - перечислены в классе `AtolOnline\Constants\PaymentObjects`;
* [ставка НДС](/docs/vats.md);
* дополнительный реквизит (тег ФФД - 1191).

Установить многие (но не все) атрибуты можно следующими способами:

```php
use AtolOnline\{
    Constants\PaymentMethods, 
    Constants\PaymentObjects, 
    Constants\VatTypes, 
    Entities\Item
};

// 1 способ - через конструктор
$item = new Item(
    'Банан', // наименование
    100, // цена
    1, // количество, вес
    'кг', // единица измерения
    VatTypes::VAT20, // ставка НДС
    PaymentObjects::SERVICE, // признак предмета расчёта
    PaymentMethods::FULL_PAYMENT // признак способа расчёта
);

// 2 способ - через сеттеры
$item = new Item();
$item->setName('Банан');
$item->setPrice(100);
$item->setQuantity(2.41);
//$item->setQuantity(2.41, 'кг');
$item->setMeasurementUnit('кг');
$item->setVatType(VatTypes::VAT20);
$item->setPaymentObject(PaymentObjects::COMMODITY);
$item->setPaymentMethod(PaymentMethods::FULL_PAYMENT);
```

Метод `setName()` проверяет входную строку на длину (до 128 символов).
Выбрасывает исключение `AtolNameTooLongException` (если слишком длинное наименование).

Метод `setPrice()` проверяет аргумент на величину (до 42949672.95) и пересчитывает общую стоимость.
Выбрасывает исключение `AtolPriceTooHighException` (если цена слишком высока).

Метод `setMeasurementUnit()` проверяет входную строку на длину (до 16 символов).
Выбрасывает исключение `AtolUnitTooLongException` (если слишком длинная строка единицы измерения).

Метод `setQuantity()` проверяет первый аргумент на величину (до 99999.999) и пересчитывает общую стоимость.
Выбрасывает исключения:
* `AtolQuantityTooHighException` (если количество слишком велико);
* `AtolPriceTooHighException` (если общая стоимость слишком велика). 

Также вторым аргументом может принимать единицу измерения количества.
В этом случае дополнительно работает сеттер `setMeasurementUnit()`.

Метод `setVatType()` задаёт тип ставки НДС, пересчитывает размер налога и общую стоимость.
Выбрасывает исключение `AtolPriceTooHighException` (если цена слишком высока).
Может принимать `null` для удаления налога.

Дополнительный реквизит устанавливается отдельным методом `setUserData()`:

```php
$item->setUserData('some data');
```

Он проверяет строку на длину (до 64 символов).
Выбрасывает исключение `AtolUserdataTooLongException` (если слишком длинный дополнительный реквизит).

Для установки признака предмета расчёта существует метод `setPaymentObject()`.
На вход следует передавать одной из значений, перечисленных в классе `AtolOnline\Constants\PaymentObjects`.

```php
$item->setPaymentObject(AtolOnline\Constants\PaymentObjects::JOB);
```

Для установки признака способа оплаты существует метод `setPaymentMethod()`.
На вход следует передавать одной из значений, перечисленных в классе `AtolOnline\Constants\PaymentMethods`.

```php
$item->setPaymentMethod(AtolOnline\Constants\PaymentMethods::FULL_PAYMENT);
```

Для получения заданных значений атрибутов реализованы соответствующие геттеры:

```php
$item->getName();
$item->getPrice();
$item->getQuantity();
$item->getMeasurementUnit();
$item->getPaymentMethod();
$item->getPaymentObject();
$item->getVat(); // возвращает объект ставки НДС либо null
$item->getUserData();
```

Для пересчёта общей стоимости и размера налога существует метод `calcSum()`.

```php
$item->calcSum();
```

Этот метод отрабатывает при вызове `setPrice()`, `setQuantity()` и `setVatType()`.
Выбрасывает исключение `AtolPriceTooHighException` (если общая сумма слишком высока).

Получить уже расчитанную общую сумму можно простым геттером:

```php
$item->getSum();
```

Объект класса приводится к JSON-строке автоматически или принудительным приведением к `string`:

```php
echo $item;
$json_string = (string)$item;
```

Чтобы получить те же данные в виде массива, нужно вызвать метод `jsonSerialize()`:

```php
$json_array = $item->jsonSerialize();
```

<a name="array"></a>
## Массив объектов предметов расчёта

> Максимальное количество объектов в массиве - 100.

Массив инициализируется следующим образом:

```php
$item_array = new AtolOnline\Entities\ItemArray();
```

Чтобы задать содержимое массива, используйте метод `set()`:

```php
$item_array->set([
    $item_object1,
    $item_object2
]);
```

Очистить его можно передачей в сеттер пустого массива:

```php
$item_array->set([]);
```

Чтобы добавить объект к существующим элементам массива, используйте метод `add()`:

```php
$item = new AtolOnline\Entities\Item('Банан', 100, 1);
$item_array->add($item);
```

Методы `set()` и `add()` проверяют количество элементов в массиве перед его обновлением.
Выбрасывают исключение `AtolTooManyItemsException` (если в массиве уже максимальное количество объектов).

Чтобы получить содержимое массива, используйте метод `get()`:

```php
$item_array->get();
```

Объект класса приводится к JSON-строке автоматически или принудительным приведением к `string`:

```php
echo $item_array;
$json_string = (string)$item_array;
```

Чтобы получить те же данные в виде массива, нужно вызвать метод `jsonSerialize()`:

```php
$json_array = $item_array->jsonSerialize();
```

---

[Вернуться к содержанию](readme.md)