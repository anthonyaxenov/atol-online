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
    Entities\MoneyTransferOperator,
    Exceptions\InvalidInnLengthException,
    Exceptions\InvalidPhoneException,
    Tests\BasicTestCase};
use Exception;

/**
 * Набор тестов для проверки работы класса оператора перевода
 */
class MoneyTransferOperatorTest extends BasicTestCase
{
    /**
     * Тестирует конструктор без передачи значений и корректное приведение к json
     *
     * @covers \AtolOnline\Entities\MoneyTransferOperator
     * @covers \AtolOnline\Entities\MoneyTransferOperator::jsonSerialize
     */
    public function testConstructorWithoutArgs(): void
    {
        $this->assertEquals('[]', (string)(new MoneyTransferOperator()));
    }

    /**
     * Тестирует конструктор с передачей значений и корректное приведение к json
     *
     * @covers \AtolOnline\Entities\MoneyTransferOperator
     * @covers \AtolOnline\Entities\MoneyTransferOperator::jsonSerialize
     * @covers \AtolOnline\Entities\MoneyTransferOperator::setName
     * @covers \AtolOnline\Entities\MoneyTransferOperator::getName
     * @covers \AtolOnline\Entities\MoneyTransferOperator::setPhones
     * @covers \AtolOnline\Entities\MoneyTransferOperator::getPhones
     * @covers \AtolOnline\Entities\MoneyTransferOperator::setInn
     * @covers \AtolOnline\Entities\MoneyTransferOperator::getInn
     * @covers \AtolOnline\Entities\MoneyTransferOperator::setAddress
     * @covers \AtolOnline\Entities\MoneyTransferOperator::getAddress
     * @throws InvalidPhoneException
     * @throws InvalidInnLengthException
     * @throws Exception
     */
    public function testConstructorWithArgs(): void
    {
        $this->assertIsAtolable(new MoneyTransferOperator('some name'), ['name' => 'some name']);
        $this->assertIsAtolable(new MoneyTransferOperator(inn: '+fasd3\qe3fs_=nac99013928czc'), ['inn' => '3399013928']);
        $this->assertIsAtolable(new MoneyTransferOperator(address: 'London'), ['address' => 'London']);
        $this->assertIsAtolable(new MoneyTransferOperator(phones: ['+122997365456']), ['phones' => ['+122997365456']]);
        $this->assertIsAtolable(new MoneyTransferOperator(
            'some name',
            '+fasd3\qe3fs_=nac99013928czc',
            'London',
            ['+122997365456'],
        ), [
            'name' => 'some name',
            'inn' => '3399013928',
            'address' => 'London',
            'phones' => ['+122997365456'],
        ]);
    }

    /**
     * Тестирует установку имён, которые приводятся к null
     *
     * @param mixed $name
     * @dataProvider providerNullableStrings
     * @covers       \AtolOnline\Entities\MoneyTransferOperator
     * @covers       \AtolOnline\Entities\MoneyTransferOperator::setName
     * @covers       \AtolOnline\Entities\MoneyTransferOperator::getName
     * @throws InvalidPhoneException
     * @throws InvalidInnLengthException
     */
    public function testNullableOperations(mixed $name): void
    {
        $this->assertNull((new MoneyTransferOperator($name))->getName());
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
     * @covers       \AtolOnline\Entities\MoneyTransferOperator
     * @covers       \AtolOnline\Entities\MoneyTransferOperator::setPhones
     * @covers       \AtolOnline\Entities\MoneyTransferOperator::getPhones
     * @throws InvalidPhoneException
     * @throws InvalidInnLengthException
     */
    public function testNullablePhones(mixed $phones): void
    {
        $agent = new MoneyTransferOperator(phones: $phones);
        $this->assertIsCollection($agent->getPhones());
        $this->assertTrue($agent->getPhones()->isEmpty());
    }

    /**
     * Тестирует установку невалидных телефонов
     *
     * @covers \AtolOnline\Entities\MoneyTransferOperator
     * @covers \AtolOnline\Entities\MoneyTransferOperator::setPhones
     * @covers \AtolOnline\Exceptions\InvalidPhoneException
     * @throws InvalidPhoneException
     * @throws InvalidInnLengthException
     */
    public function testInvalidPhoneException(): void
    {
        $this->expectException(InvalidPhoneException::class);
        (new MoneyTransferOperator(phones: [
            '12345678901234567', // good
            '+123456789012345678', // good
            '12345678901234567890', // bad
            '+12345678901234567890', // bad
        ]));
    }

    /**
     * Тестирует исключение о корректной длине ИНН
     *
     * @covers \AtolOnline\Entities\MoneyTransferOperator
     * @covers \AtolOnline\Entities\MoneyTransferOperator::setInn
     * @covers \AtolOnline\Entities\MoneyTransferOperator::getInn
     * @throws InvalidInnLengthException
     */
    public function testValidInn(): void
    {
        $this->assertEquals('1234567890', (new MoneyTransferOperator())->setInn('1234567890')->getInn());
        $this->assertEquals('123456789012', (new MoneyTransferOperator())->setInn('123456789012')->getInn());
    }

    /**
     * Тестирует исключение о некорректной длине ИНН (10 цифр)
     *
     * @covers \AtolOnline\Entities\MoneyTransferOperator
     * @covers \AtolOnline\Entities\MoneyTransferOperator::setInn
     * @covers \AtolOnline\Exceptions\InvalidInnLengthException
     * @throws InvalidInnLengthException
     */
    public function testInvalidInn10(): void
    {
        $this->expectException(InvalidInnLengthException::class);
        (new MoneyTransferOperator())->setInn('12345678901');
    }

    /**
     * Тестирует исключение о некорректной длине ИНН (12 цифр)
     *
     * @covers \AtolOnline\Entities\MoneyTransferOperator
     * @covers \AtolOnline\Entities\MoneyTransferOperator::setInn
     * @covers \AtolOnline\Exceptions\InvalidInnLengthException
     * @throws InvalidInnLengthException
     */
    public function testInvalidInn12(): void
    {
        $this->expectException(InvalidInnLengthException::class);
        (new MoneyTransferOperator())->setInn('1234567890123');
    }
}
