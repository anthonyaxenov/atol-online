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
    Entities\Supplier,
    Exceptions\InvalidInnLengthException,
    Exceptions\InvalidPhoneException,
    Tests\BasicTestCase};
use Exception;

/**
 * Набор тестов для проверки работы класса поставщика
 */
class SupplierTest extends BasicTestCase
{
    /**
     * Тестирует конструктор без передачи значений и корректное приведение к json
     *
     * @covers \AtolOnline\Entities\Supplier
     * @covers \AtolOnline\Entities\Supplier::jsonSerialize
     */
    public function testConstructorWithoutArgs(): void
    {
        $this->assertSame('[]', (string)(new Supplier()));
    }

    /**
     * Тестирует конструктор с передачей значений и корректное приведение к json
     *
     * @covers \AtolOnline\Entities\Supplier
     * @covers \AtolOnline\Entities\Supplier::jsonSerialize
     * @covers \AtolOnline\Entities\Supplier::setName
     * @covers \AtolOnline\Entities\Supplier::getName
     * @covers \AtolOnline\Entities\Supplier::setPhones
     * @covers \AtolOnline\Entities\Supplier::getPhones
     * @covers \AtolOnline\Entities\Supplier::setInn
     * @covers \AtolOnline\Entities\Supplier::getInn
     * @throws InvalidPhoneException
     * @throws InvalidInnLengthException
     * @throws Exception
     */
    public function testConstructorWithArgs(): void
    {
        $this->assertIsAtolable(new Supplier('some name'), ['name' => 'some name']);
        $this->assertIsAtolable(new Supplier(inn: '+fasd3\qe3fs_=nac99013928czc'), ['inn' => '3399013928']);
        $this->assertIsAtolable(new Supplier(phones: ['+122997365456']), ['phones' => ['+122997365456']]);
        $this->assertIsAtolable(
            new Supplier(
                'some name',
                '+fasd3\qe3fs_=nac99013928czc',
                ['+122997365456'],
            ),
            [
                'name' => 'some name',
                'inn' => '3399013928',
                'phones' => ['+122997365456'],
            ]
        );
    }

    /**
     * Тестирует установку имён, которые приводятся к null
     *
     * @param mixed $name
     * @dataProvider providerNullableStrings
     * @covers       \AtolOnline\Entities\Supplier
     * @covers       \AtolOnline\Entities\Supplier::setName
     * @covers       \AtolOnline\Entities\Supplier::getName
     * @throws InvalidPhoneException
     * @throws InvalidInnLengthException
     */
    public function testNullableOperations(mixed $name): void
    {
        $this->assertNull((new Supplier($name))->getName());
    }

    /**
     * Провайдер массивов телефонов, которые приводятся к null
     *
     * @return array<array>
     */
    public function providerNullablePhonesArrays(): array
    {
        return [
            [[]],
            [null],
            [collect()],
        ];
    }

    /**
     * Тестирует установку пустых телефонов
     *
     * @dataProvider providerNullablePhonesArrays
     * @covers       \AtolOnline\Entities\Supplier
     * @covers       \AtolOnline\Entities\Supplier::setPhones
     * @covers       \AtolOnline\Entities\Supplier::getPhones
     * @throws InvalidPhoneException
     * @throws InvalidInnLengthException
     */
    public function testNullablePhones(mixed $phones): void
    {
        $agent = new Supplier(phones: $phones);
        $this->assertIsCollection($agent->getPhones());
        $this->assertTrue($agent->getPhones()->isEmpty());
    }

    /**
     * Тестирует установку невалидных телефонов
     *
     * @covers \AtolOnline\Entities\Supplier
     * @covers \AtolOnline\Entities\Supplier::setPhones
     * @covers \AtolOnline\Exceptions\InvalidPhoneException
     * @throws InvalidPhoneException
     * @throws InvalidInnLengthException
     */
    public function testInvalidPhoneException(): void
    {
        $this->expectException(InvalidPhoneException::class);
        (new Supplier(phones: [
            '12345678901234567', // good
            '+123456789012345678', // good
            '12345678901234567890', // bad
            '+12345678901234567890', // bad
        ]));
    }

    /**
     * Тестирует исключение о корректной длине ИНН
     *
     * @covers \AtolOnline\Entities\Supplier
     * @covers \AtolOnline\Entities\Supplier::setInn
     * @covers \AtolOnline\Entities\Supplier::getInn
     * @throws InvalidInnLengthException
     */
    public function testValidInn(): void
    {
        $this->assertSame('1234567890', (new Supplier())->setInn('1234567890')->getInn());
        $this->assertSame('123456789012', (new Supplier())->setInn('123456789012')->getInn());
    }

    /**
     * Тестирует исключение о некорректной длине ИНН (10 цифр)
     *
     * @covers \AtolOnline\Entities\Supplier
     * @covers \AtolOnline\Entities\Supplier::setInn
     * @covers \AtolOnline\Exceptions\InvalidInnLengthException
     * @throws InvalidInnLengthException
     */
    public function testInvalidInn10(): void
    {
        $this->expectException(InvalidInnLengthException::class);
        (new Supplier())->setInn('12345678901');
    }

    /**
     * Тестирует исключение о некорректной длине ИНН (12 цифр)
     *
     * @covers \AtolOnline\Entities\Supplier
     * @covers \AtolOnline\Entities\Supplier::setInn
     * @covers \AtolOnline\Exceptions\InvalidInnLengthException
     * @throws InvalidInnLengthException
     */
    public function testInvalidInn12(): void
    {
        $this->expectException(InvalidInnLengthException::class);
        (new Supplier())->setInn('1234567890123');
    }
}
