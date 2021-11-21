# Работа с компанией (продавцом)

[Вернуться к содержанию](readme.md)

---

Объект компании инициализируется следующим образом:

```php
$customer = new AtolOnline\Entities\Company();
```

У объекта компании должны быть указаны все следующие атрибуты:
* email (тег ФФД 1117);
* ИНН (тег ФФД 1018);
* тип системы налогообложения (тег ФФД 1055) - все типы перечислены в классе `AtolOnline\Constants\SnoTypes`;
* адрес места расчётов (тег ФФД 1187) - для интернет-сервисов указывается URL с протоколом.

> Все эти атрибуты являются **обязательными**.  
> Для тестового режима используйте значения ИНН и адреса места расчётов, [указанные здесь](https://online.atol.ru/files/ffd/test_sreda.txt).

Указать эти атрибуты можно двумя способами:

```php
// 1 способ - через конструктор (все аргументы обязательны)
$company = new AtolOnline\Entities\Company(
    'company@example.com' // email
    AtolOnline\Constants\SnoTypes::OSN, // тип СНО
    '5544332219', // номер ИНН
    'https://v4.online.atol.ru', // адрес места расчётов
);

// 2 способ - через сеттеры
$company
    ->setEmail('company@example.com')
    ->setInn('5544332219')
    ->setSno(AtolOnline\Constants\SnoTypes::USN_INCOME)
    ->setPaymentAddress('https://v4.online.atol.ru');

// либо комбинация этих способов
```

Получить установленные значения параметров можно через геттеры:

```php
$company->getInn();
$company->getEmail();
$company->getPaymentAddress();
$company->getSno();
```

Объект класса приводится к JSON-строке автоматически или принудительно:

```php
echo $company;
$json_string = (string)$company;
```

Чтобы получить те же данные в виде массива, нужно вызвать метод `jsonSerialize()`:

```php
$json_array = $company->jsonSerialize();
```

---

[Вернуться к содержанию](readme.md)