# Работа с клиентами (покупателями)

[Вернуться к содержанию](readme.md)

---

Объект покупателя инициализируется следующим образом:

```php
$customer = new AtolOnline\Entities\Client();
```

У объекта покупателя могут быть указаны любые из следующих атрибутов:
* email (тег ФФД 1008);
* ИНН (тег ФФД 1128);
* наименование (тег ФФД 1127);
* номер телефона (тег ФФД 1008).

> Все эти атрибуты являются **необязательными**.  
> Если указаны одновременно и email, и номер телефона, то ОФД отправит чек только на email.

Указать эти атрибуты можно двумя способами:

```php
// 1 способ - через конструктор
$customer = new AtolOnline\Entities\Client(
    'John Doe', // наименование
    'john@example.com', // email
    '+1/22/99*73s dsdas654 5s6', // номер телефона +122997365456
    '+fasd3\qe3fs_=nac990139928czc' // номер ИНН 3399013928
);

// 2 способ - через сеттеры
$customer = (new AtolOnline\Entities\Client())
    ->setEmail('john@example.com')
    ->setInn('+fasd3\q3fs_=nac9901 3928c-c')
    ->setName('John Doe')
    ->setPhone('+1/22/99*73s dsdas654 5s6');

// либо комбинация этих способов
```

Получить установленные значения атрибутов можно через геттеры:

```php
$customer->getInn();
$customer->getEmail();
$customer->getName();
$customer->getPhone();
```

Объект класса приводится к JSON-строке автоматически или принудительно:

```php
echo $customer;
$json_string = (string)$customer;
```

Чтобы получить те же данные в виде массива, нужно вызвать метод `jsonSerialize()`:

```php
$json_array = $customer->jsonSerialize();
```

---

[Вернуться к содержанию](readme.md)
