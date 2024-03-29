<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Tests\Entities;

use AtolOnline\{
    Entities\Client,
    Exceptions\InvalidEmailException,
    Exceptions\InvalidInnLengthException,
    Exceptions\InvalidPhoneException,
    Exceptions\TooLongClientNameException,
    Exceptions\TooLongEmailException,
    Helpers,
    Tests\BasicTestCase};
use BadMethodCallException;
use Exception;

/**
 * Набор тестов для проверки работы класса покупателя
 */
class ClientTest extends BasicTestCase
{
    /**
     * Тестирует конструктор без передачи значений и приведение к json
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::jsonSerialize
     * @throws Exception
     */
    public function testConstructorWithoutArgs(): void
    {
        $this->assertIsAtolable(new Client());
    }

    /**
     * Тестирует конструктор с передачей значений и приведение к json
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::jsonSerialize
     * @covers \AtolOnline\Entities\Client::setName
     * @covers \AtolOnline\Entities\Client::setPhone
     * @covers \AtolOnline\Entities\Client::setEmail
     * @covers \AtolOnline\Entities\Client::setInn
     * @covers \AtolOnline\Entities\Client::getName
     * @covers \AtolOnline\Entities\Client::getPhone
     * @covers \AtolOnline\Entities\Client::getEmail
     * @covers \AtolOnline\Entities\Client::getInn
     * @throws Exception
     */
    public function testConstructorWithArgs(): void
    {
        $this->assertIsAtolable(new Client('John Doe'), ['name' => 'John Doe']);
        $this->assertIsAtolable(new Client(phone: '+1/22/99*73s dsdas654 5s6'), ['phone' => '+122997365456']);
        $this->assertIsAtolable(new Client(email: 'john@example.com'), ['email' => 'john@example.com']);
        $this->assertIsAtolable(new Client(inn: '+fasd3\qe3fs_=nac99013928czc'), ['inn' => '3399013928']);
        $this->assertIsAtolable(
            new Client(
                'John Doe',
                '+1/22/99*73s dsdas654 5s6', // +122997365456
                'john@example.com',
                '+fasd3\qe3fs_=nac99013928czc' // 3399013928
            ),
            [
                'name' => 'John Doe',
                'phone' => '+122997365456',
                'email' => 'john@example.com',
                'inn' => '3399013928',
            ]
        );
    }

    /**
     * Тестирует установку имён, которые приводятся к null
     *
     * @param mixed $name
     * @dataProvider providerNullableStrings
     * @covers       \AtolOnline\Entities\Client
     * @covers       \AtolOnline\Entities\Client::setName
     * @covers       \AtolOnline\Entities\Client::getName
     * @throws TooLongClientNameException
     */
    public function testNullableNames(mixed $name): void
    {
        $this->assertNull((new Client())->setName($name)->getName());
    }

    /**
     * Тестирует установку валидного имени
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::setName
     * @covers \AtolOnline\Entities\Client::getName
     * @throws TooLongClientNameException
     */
    public function testValidName(): void
    {
        $name = Helpers::randomStr();
        $this->assertSame($name, (new Client())->setName($name)->getName());
    }

    /**
     * Тестирует установку невалидного имени
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::setName
     * @covers \AtolOnline\Exceptions\TooLongClientNameException
     */
    public function testInvalidName(): void
    {
        $this->expectException(TooLongClientNameException::class);
        (new Client())->setName(Helpers::randomStr(400));
    }

    /**
     * Тестирует установку телефонов, которые приводятся к null
     *
     * @param mixed $phone
     * @dataProvider providerNullablePhones
     * @covers       \AtolOnline\Entities\Client
     * @covers       \AtolOnline\Entities\Client::setPhone
     * @covers       \AtolOnline\Entities\Client::getPhone
     * @throws InvalidPhoneException
     */
    public function testNullablePhones(mixed $phone): void
    {
        $this->assertNull((new Client())->setPhone($phone)->getPhone());
    }

    /**
     * Тестирует установку валидного телефона
     *
     * @throws InvalidPhoneException
     * @todo актуализировать при доработатанной валидации
     * @dataProvider providerValidPhones
     * @covers       \AtolOnline\Entities\Client
     * @covers       \AtolOnline\Entities\Client::setPhone
     * @covers       \AtolOnline\Entities\Client::getPhone
     */
    public function testValidPhone(string $input, string $output): void
    {
        $this->assertSame($output, (new Client())->setPhone($input)->getPhone());
    }

    /**
     * Тестирует установку невалидного телефона
     *
     * @throws InvalidPhoneException
     * @todo актуализировать при доработатанной валидации
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::setPhone
     * @covers \AtolOnline\Exceptions\InvalidPhoneException
     */
    public function testInvalidPhoneException(): void
    {
        $this->expectException(InvalidPhoneException::class);
        (new Client())->setPhone(Helpers::randomStr(500));
    }

    /**
     * Тестирует установку валидных email-ов
     *
     * @param mixed $email
     * @dataProvider providerValidEmails
     * @covers       \AtolOnline\Entities\Client
     * @covers       \AtolOnline\Entities\Client::setEmail
     * @covers       \AtolOnline\Entities\Client::getEmail
     * @throws TooLongEmailException
     * @throws InvalidEmailException
     */
    public function testValidEmails(mixed $email): void
    {
        $this->assertSame($email, (new Client())->setEmail($email)->getEmail());
    }

    /**
     * Тестирует установку слишком длинного email
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::setEmail
     * @covers \AtolOnline\Exceptions\TooLongEmailException
     * @throws TooLongEmailException
     * @throws InvalidEmailException
     */
    public function testTooLongEmail(): void
    {
        $this->expectException(TooLongEmailException::class);
        (new Client())->setEmail(Helpers::randomStr(65));
    }

    /**
     * Тестирует установку невалидного email
     *
     * @param mixed $email
     * @dataProvider providerInvalidEmails
     * @covers       \AtolOnline\Entities\Client
     * @covers       \AtolOnline\Entities\Client::setEmail
     * @covers       \AtolOnline\Exceptions\InvalidEmailException
     * @throws TooLongEmailException
     * @throws InvalidEmailException
     */
    public function testInvalidEmail(mixed $email): void
    {
        $this->expectException(InvalidEmailException::class);
        (new Client())->setEmail($email);
    }

    /**
     * Тестирует исключение о корректной длине ИНН
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::setInn
     * @covers \AtolOnline\Entities\Client::getInn
     * @throws InvalidInnLengthException
     */
    public function testValidInn(): void
    {
        $this->assertSame('1234567890', (new Client())->setInn('1234567890')->getInn());
        $this->assertSame('123456789012', (new Client())->setInn('123456789012')->getInn());
    }

    /**
     * Тестирует исключение о некорректной длине ИНН (10 цифр)
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::setInn
     * @covers \AtolOnline\Exceptions\InvalidInnLengthException
     * @throws InvalidInnLengthException
     */
    public function testInvalidInn10(): void
    {
        $this->expectException(InvalidInnLengthException::class);
        (new Client())->setInn('12345678901');
    }

    /**
     * Тестирует исключение о некорректной длине ИНН (12 цифр)
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::setInn
     * @covers \AtolOnline\Exceptions\InvalidInnLengthException
     * @throws InvalidInnLengthException
     */
    public function testInvalidInn12(): void
    {
        $this->expectException(InvalidInnLengthException::class);
        (new Client())->setInn('1234567890123');
    }

    /**
     * Тестирует обращение к атрибутам объекта как к элементам массива
     *
     * @covers \AtolOnline\Entities\Entity::offsetGet
     * @covers \AtolOnline\Entities\Entity::offsetExists
     * @return void
     */
    public function testOffsetGetExists(): void
    {
        $client = new Client('John Doe');
        $this->assertSame('John Doe', $client['name']);
        $this->assertTrue(isset($client['name']));
        $this->assertFalse(isset($client['qwerty']));
    }

    /**
     * Тестирует выброс исключения при попытке задать значение атрибуту объекта как элементу массива
     *
     * @covers \AtolOnline\Entities\Entity::offsetSet
     * @return void
     */
    public function testBadMethodCallExceptionBySet(): void
    {
        $this->expectException(BadMethodCallException::class);
        $client = new Client('John Doe');
        $client['name'] = 'qwerty';
    }

    /**
     * Тестирует выброс исключения при попытке удалить значение атрибута объекта как элемент массива
     *
     * @covers \AtolOnline\Entities\Entity::offsetUnset
     * @return void
     */
    public function testBadMethodCallExceptionByUnset(): void
    {
        $this->expectException(BadMethodCallException::class);
        $client = new Client('John Doe');
        unset($client['name']);
    }
}
