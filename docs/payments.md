# Работа с оплатами

## Один объект

Объект оплаты инициализируется следующим образом:

```php
$payment = new AtolOnline\Entities\Payment();
```

У объекта оплаты должны быть указаны все следующие атрибуты:
* тип оплаты (теги ФФД: 1031, 1081, 1215, 1216, 1217) - все типы перечислены в классе `AtolOnline\Constants\PaymentTypes` (по умолчанию `ELECTRON`)
* сумма оплаты (теги ФФД: 1031, 1081, 1215, 1216, 1217; по умолчанию 0)

> Все эти атрибуты являются **обязательными**.

Установить атрибуты можно следующими способами:

```php
// 1 способ - через конструктор
$payment = new AtolOnline\Entities\Payment(
    AtolOnline\Constants\PaymentTypes::OTHER, // тип оплаты
    123.45 // сумма оплаты
);

// 2 способ - через сеттер
$payment = (new AtolOnline\Entities\Payment())
    ->setType(AtolOnline\Constants\PaymentTypes::OTHER) // тип оплаты
    ->setSum(123.45); // сумма оплаты
```

Размер налога высчитывается автоматически из общей суммы.
Сумму, от которой нужно расчитать размер налога, можно передать следующими способами:

```php
// 1 способ - через конструктор
$payment = new AtolOnline\Entities\Payment(
    AtolOnline\Constants\PaymentTypes::CASH, // тип оплаты
    1234.56 // сумма оплаты в рублях
);

// 2 способ - через сеттер
$payment = (new AtolOnline\Entities\Payment())
    ->setType(AtolOnline\Constants\PaymentTypes::CASH) // тип оплаты
    ->setSum(1234.56); // сумма оплаты в рублях
```

Получить установленную сумму оплаты в рублях можно через геттер `getSum()`:

```php
var_dump($payment->getSum());
```

Объект класса приводится к JSON-строке автоматически или принудительным приведением к `string`:

```php
echo $payment;
$json_string = (string)$payment;
```

Чтобы получить те же данные в виде массива, нужно вызвать метод `jsonSerialize()`:

```php
$json_array = $payment->jsonSerialize();
```

<a name="array"></a>
## Массив объектов оплат

> Максимальное количество объектов в массиве - 10.

Массив инициализируется следующим образом:

```php
$payment_array = new AtolOnline\Entities\PaymentArray();
```

Чтобы задать содержимое массива, используйте метод `set()`:

```php
use AtolOnline\{Constants\PaymentTypes, Entities\Payment};

$payment_array->set([
    new Payment(PaymentTypes::ELECTRON, 123),
    new Payment(PaymentTypes::ELECTRON, 53.2),
    new Payment(PaymentTypes::ELECTRON, 23.99),
    new Payment(PaymentTypes::ELECTRON, 11.43)
]);
```

Очистить его можно передачей в сеттер пустого массива:

```php
$payment_array->set([]);
```

Чтобы добавить объект к существующим элементам массива, используйте метод `add()`:

```php
use AtolOnline\{Constants\PaymentTypes, Entities\Payment};

$payment = new Payment(PaymentTypes::PRE_PAID, 20);
$payment_array->add($payment);
```

Методы `set()` и `add()` проверяют количество элементов в массиве перед его обновлением.
Выбрасывают исключение `AtolTooManyPaymentsException` (если в массиве уже максимальное количество объектов).

Чтобы получить содержимое массива, используйте метод `get()`:

```php
$payment_array->get();
```

Объект класса приводится к JSON-строке автоматически или принудительным приведением к `string`:

```php
echo $payment_array;
$json_string = (string)$payment_array;
```

Чтобы получить те же данные в виде массива, нужно вызвать метод `jsonSerialize()`:

```php
$json_array = $payment_array->jsonSerialize();
```
