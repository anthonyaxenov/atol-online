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
    Exceptions\TooLongClientContactException,
    Exceptions\TooLongClientNameException,
    Exceptions\TooLongEmailException,
    Helpers,
    Tests\BasicTestCase};

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
     */
    public function testConstructorWithoutArgs(): void
    {
        $this->assertAtolable(new Client(), []);
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
     */
    public function testConstructorWithArgs(): void
    {
        $this->assertAtolable(new Client('John Doe'), ['name' => 'John Doe']);
        $this->assertAtolable(new Client(email: 'john@example.com'), ['email' => 'john@example.com']);
        $this->assertAtolable(new Client(phone: '+1/22/99*73s dsdas654 5s6'), ['phone' => '+122997365456']);
        $this->assertAtolable(new Client(inn: '+fasd3\qe3fs_=nac99013928czc'), ['inn' => '3399013928']);
        $this->assertAtolable(new Client(
            'John Doe',
            'john@example.com',
            '+1/22/99*73s dsdas654 5s6', // +122997365456
            '+fasd3\qe3fs_=nac99013928czc' // 3399013928
        ), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+122997365456',
            'inn' => '3399013928',
        ]);
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
        $this->assertEquals($name, (new Client())->setName($name)->getName());
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
     * @throws TooLongClientContactException
     */
    public function testNullablePhones(mixed $phone): void
    {
        $this->assertNull((new Client())->setPhone($phone)->getPhone());
    }

    /**
     * Тестирует установку валидного телефона
     *
     * @todo актуализировать при доработатанной валидации
     * @dataProvider providerValidPhones
     * @covers       \AtolOnline\Entities\Client
     * @covers       \AtolOnline\Entities\Client::setPhone
     * @covers       \AtolOnline\Entities\Client::getPhone
     * @throws TooLongClientContactException
     */
    public function testValidPhone(string $input, string $output): void
    {
        $this->assertEquals($output, (new Client())->setPhone($input)->getPhone());
    }

    /**
     * Тестирует установку невалидного телефона
     *
     * @todo актуализировать при доработатанной валидации
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::setPhone
     * @covers \AtolOnline\Exceptions\TooLongClientContactException
     * @throws TooLongClientContactException
     */
    public function testTooLongClientPhone(): void
    {
        $this->expectException(TooLongClientContactException::class);
        (new Client())->setPhone('99999999999999999999999999999999999999999999999999999999999999999999999999');
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
        $this->assertEquals($email, (new Client())->setEmail($email)->getEmail());
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
        $this->assertEquals('1234567890', (new Client())->setInn('1234567890')->getInn());
        $this->assertEquals('123456789012', (new Client())->setInn('123456789012')->getInn());
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
}
