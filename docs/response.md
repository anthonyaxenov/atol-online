# Обработка ответа API

[Вернуться к содержанию](readme.md#toc)

---

Объект класса `AtolOnline\Api\AtolResponse` возвращается всеми методами, которые обращаются к АТОЛ Онлайн API.

Поскольку классы `AtolOnline\Api\Fiscalizer` и `AtolOnline\Api\KktMonitor` наследуются от
абстрактного `AtolOnline\Api\AtolClient`, они оба предоставляют метод `getLastReponse()` для возврата последнего
полученного ответа от API.

Таким образом, а общем случае необязательно сразу сохранять ответ в переменную:

```php
$response = $kkt->sell($receipt);
```

Достаточно обратиться к нему позднее:

```php
$kkt->sell($receipt);
// ...
$response = $kkt->getLastResponse();
```

Однако, при сложной логике и многократных запросах следует пользоваться этим с умом и осторожностью.

Объект `AtolResponse` содержит в себе HTTP-код ответа, массив заголовков и JSON-декодированные данные тела ответа.

```php
$headers = $response->getHeaders(); // вернёт заголовки
$code = $response->getCode(); // вернёт код ответа
$body = $response->getContent(); // вернёт JSON-декодированный объект тела ответа
```

Обращаться к полям тела ответа можно опуская вызов метода `getContent()`:

```php
// вернёт значение поля uuid
$uuid = $response->getContent()->uuid;
$uuid = $response->uuid; 

// вернёт текст ошибки
$err_text = $response->getContent()->error->text;
$err_text = $response->error->text;
```

Проверка успешности операции доступна через метод `isSuccessful()`:

```php
$response->isSuccessful(); // вернёт true, если ошибок нет
```

---

[Вернуться к содержанию](readme.md#toc)
