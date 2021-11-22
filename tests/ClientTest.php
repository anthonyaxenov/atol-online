<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnlineTests;

use AtolOnline\{
    Entities\Client,
    Exceptions\InvalidEmailException,
    Exceptions\InvalidInnLengthException,
    Exceptions\TooLongClientContactException,
    Exceptions\TooLongClientNameException,
    Exceptions\TooLongEmailException,
    Exceptions\TooLongItemNameException,
    Helpers};

/**
 * Набор тестов для проверки работы класс покупателя
 */
class ClientTest extends BasicTestCase
{
    /**
     * Провайдер строк, которые приводятся к null
     *
     * @return array<array<string|null>>
     */
    public function providerNullableStrings(): array
    {
        return [
            [''],
            [' '],
            [null],
            ["\n\r\t"],
        ];
    }

    /**
     * Провайдер телефонов, которые приводятся к null
     *
     * @return array<array<string>>
     */
    public function providerNullablePhones(): array
    {
        return array_merge(
            $this->providerNullableStrings(),
            [
                [Helpers::randomStr(10, false)],
                ["asdfgvs \n\rtt\t*/(*&%^*$%"],
            ]
        );
    }

    /**
     * Провайдер невалидных email-ов
     *
     * @return array<array<string>>
     */
    public function providerInvalidEmails(): array
    {
        return [
            ['@example'],
            [Helpers::randomStr(15)],
            ['@example.com'],
            ['abc.def@mail'],
            ['.abc@mail.com'],
            ['example@example'],
            ['abc..def@mail.com'],
            ['abc.def@mail..com'],
            ['abc.def@mail#archive.com'],
        ];
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Тестирует приведение покупателя к json
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::jsonSerialize
     */
    public function testAtolable(): void
    {
        $this->assertAtolable(new Client());
    }

    /**
     * Тестирует конструктор покупателя без передачи значений
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::jsonSerialize
     */
    public function testConstructorWithoutArgs(): void
    {
        $this->assertEquals('{}', (string)(new Client()));
    }

    /**
     * Тестирует конструктор с передачей значений (внутри работают сеттеры)
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::jsonSerialize
     * @covers \AtolOnline\Entities\Client::setName
     * @covers \AtolOnline\Entities\Client::setPhone
     * @covers \AtolOnline\Entities\Client::setEmail
     * @covers \AtolOnline\Entities\Client::setInn
     */
    public function testConstructorWithArgs(): void
    {
        $customer = new Client(
            'John Doe',
            'john@example.com',
            '+1/22/99*73s dsdas654 5s6', // +122997365456
            '+fasd3\qe3fs_=nac99013928czc' // 3399013928
        );
        $this->assertAtolable($customer, [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+122997365456',
            'inn' => '3399013928',
        ]);
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Тестирует установку имён покупателя, которые приводятся к null
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
        $customer = (new Client())->setName($name);
        $this->assertNull($customer->getName());
    }

    /**
     * Тестирует установку валидного имени покупателя
     *
     * @covers \AtolOnline\Entities\Client
     * @covers \AtolOnline\Entities\Client::setName
     * @covers \AtolOnline\Entities\Client::getName
     * @throws TooLongItemNameException
     */
    public function testValidName(): void
    {
        $name = Helpers::randomStr();
        $customer = (new Client())->setName($name);
        $this->assertEquals($name, $customer->getName());
    }

    /**
     * Тестирует установку невалидного имени покупателя
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

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Тестирует установку телефонов покупателя, которые приводятся к null
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
        $customer = (new Client())->setPhone($phone);
        $this->assertNull($customer->getPhone());
    }

    /**
     * Тестирует установку валидного телефона покупателя
     *
     * @dataProvider providerValidPhones
     * @covers       \AtolOnline\Entities\Client
     * @covers       \AtolOnline\Entities\Client::setPhone
     * @covers       \AtolOnline\Entities\Client::getPhone
     * @throws TooLongClientContactException
     */
    public function testValidPhone(string $input, string $output): void
    {
        $customer = (new Client())->setPhone($input);
        $this->assertEquals($output, $customer->getPhone());
    }

    /**
     * Тестирует установку невалидного телефона покупателя
     *
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

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Тестирует установку валидных email-ов покупателя
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
        $customer = (new Client())->setEmail($email);
        $this->assertEquals($email, $customer->getEmail());
    }

    /**
     * Тестирует установку слишком длинного email покупателя
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
     * Тестирует установку невалидного email покупателя
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

    //------------------------------------------------------------------------------------------------------------------

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
        $customer = (new Client())->setInn('1234567890');
        $this->assertEquals('1234567890', $customer->getInn());
        $customer = $customer->setInn('123456789012');
        $this->assertEquals('123456789012', $customer->getInn());
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
