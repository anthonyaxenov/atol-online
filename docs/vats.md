# Работа со ставками НДС

[Вернуться к содержанию](readme.md)

---

## Один объект

Объект ставки НДС инициализируется следующим образом:

```php
$vat = new AtolOnline\Entities\Vat();
```

У объекта ставки должны быть указаны все следующие атрибуты:
* тип ставки (теги ФФД: 1199 для предмета расчёта; 1105, 1104, 1103, 1102, 1107, 1106 для чека) - все типы перечислены в классе `AtolOnline\Constants\VatTypes` (по умолчанию `NONE`)
* размер налога (теги ФФД: 1200 для предмета расчёта; 1105, 1104, 1103, 1102, 1107, 1106 для чека)

> Все эти атрибуты являются **обязательными**.

Установить тип ставки НДС можно следующими способами:

```php
// 1 способ - через конструктор
$vat = new AtolOnline\Entities\Vat(
    AtolOnline\Constants\VatTypes::VAT20 // тип ставки
);

// 2 способ - через сеттер
$vat = (new AtolOnline\Entities\Vat())
    ->setType(AtolOnline\Constants\VatTypes::VAT20); // тип ставки
```

Размер налога высчитывается автоматически из общей суммы.
Сумму, от которой нужно расчитать размер налога, можно передать следующими способами:

```php
// 1 способ - через конструктор
$vat = new AtolOnline\Entities\Vat(
    AtolOnline\Constants\VatTypes::VAT10, // тип ставки
    1234.56 // общая сумма в рублях
);

// 2 способ - через сеттер
$vat = (new AtolOnline\Entities\Vat())
    ->setType(AtolOnline\Constants\VatTypes::VAT10) // тип ставки
    ->setSum(150); // общая сумма в рублях
```

Сумму можно установить и до установки типа ставки.
Объект её запомнит и пересчитает итоговый размер налога при смене типа ставки:

```php
$vat = (new AtolOnline\Entities\Vat())
    ->setSum(150) // общая сумма в рублях
    ->setType(AtolOnline\Constants\VatTypes::VAT10); // тип ставки
```

Получить установленную расчётную сумму в рублях можно через геттер `getSum()`:

```php
var_dump($vat->getSum()); 
// double(150) 
```

Получить расчитанный размер налога в рублях можно через геттер `getFinalSum()`:

```php
var_dump($vat->getFinalSum()); 
// double(15): для примера выше это 10% от 150р = 15р
```

Общую сумму, из которой расчитывается размер налога, можно увеличить, используя метод `addSum()`.
Указанная в рублях сумма увеличится и итоговый размер налога пересчитается.
Для уменьшения суммы следует передать отрицательное число.

Разберём комплексный пример изменения типа ставки и расчётной суммы:

```php
use AtolOnline\{Entities\Vat, Constants\VatTypes};

$vat = new Vat(VatTypes::VAT20, 120);
echo "НДС20 от 120р: ";
var_dump($vat->getFinalSum());

echo "НДС10 от 120р: ";
$vat->setType(VatTypes::VAT10);
var_dump($vat->getFinalSum());

$vat->addSum(40);
echo "НДС10 от {$vat->getSum()}р: ";
var_dump($vat->getFinalSum());

$vat->setType(VatTypes::VAT20)->addSum(-20);
echo "НДС20 от {$vat->getSum()}р: ";
var_dump($vat->getFinalSum());

$vat->setType(VatTypes::VAT120);
echo "НДС20/120 от {$vat->getSum()}р: ";
var_dump($vat->getFinalSum());
```

Результат будет следующим:

```
НДС20 от 120р:
double(24)
НДС10 от 120р:
double(12)
НДС10 от 160р:
double(16)
НДС20 от 140р:
double(28)
НДС20/120 от 140р:
double(23.33)
```

Объект класса приводится к JSON-строке автоматически или принудительным приведением к `string`:

```php
echo $vat;
$json_string = (string)$vat;
```

Чтобы получить те же данные в виде массива, нужно вызвать метод `jsonSerialize()`:

```php
$json_array = $vat->jsonSerialize();
```

<a name="array"></a>
## Массив объектов ставок НДС

> Максимальное количество в массиве - 6.

Массив инициализируется следующим образом:

```php
$vat_array = new AtolOnline\Entities\VatArray();
```

Чтобы задать содержимое массива, используйте метод `set()`:

```php
use AtolOnline\{Constants\VatTypes, Entities\Vat};

$vat_array->set([
    new Vat(VatTypes::VAT10, 123),
    new Vat(VatTypes::VAT110, 53.2),
    new Vat(VatTypes::VAT20, 23.99),
    new Vat(VatTypes::VAT120, 11.43)
]);
```

Очистить его можно передачей в сеттер пустого массива:

```php
$vat_array->set([]);
```

Чтобы добавить объект к существующим элементам массива, используйте метод `add()`:

```php
use AtolOnline\{Constants\VatTypes, Entities\Vat};

$vat = new Vat(VatTypes::VAT20, 20);
$vat_array->add($vat);
```

Методы `set()` и `add()` проверяют количество элементов в массиве перед его обновлением.
Выбрасывают исключение `AtolTooManyVatsException` (если в массиве уже максимальное количество объектов).

Чтобы получить содержимое массива, используйте метод `get()`:

```php
$vat_array->get();
```

Объект класса приводится к JSON-строке автоматически или принудительным приведением к `string`:

```php
echo $vat_array;
$json_string = (string)$vat_array;
```

Чтобы получить те же данные в виде массива, нужно вызвать метод `jsonSerialize()`:

```php
$json_array = $vat_array->jsonSerialize();
```

---

[Вернуться к содержанию](readme.md)