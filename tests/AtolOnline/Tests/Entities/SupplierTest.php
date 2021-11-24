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
    Exceptions\InvalidPhoneException,
    Tests\BasicTestCase};

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
        $this->assertEquals('[]', (string)(new Supplier()));
    }

    /**
     * Тестирует конструктор с передачей значений и корректное приведение к json
     *
     * @covers \AtolOnline\Entities\Supplier
     * @covers \AtolOnline\Entities\Supplier::jsonSerialize
     * @covers \AtolOnline\Entities\Supplier::setPhones
     * @covers \AtolOnline\Entities\Supplier::getPhones
     * @throws InvalidPhoneException
     */
    public function testConstructorWithArgs(): void
    {
        $this->assertAtolable(new Supplier(['+122997365456']), ['phones' => ['+122997365456']]);
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
     */
    public function testNullablePhones(mixed $phones): void
    {
        $agent = new Supplier($phones);
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
     */
    public function testInvalidPhoneException(): void
    {
        $this->expectException(InvalidPhoneException::class);
        (new Supplier())->setPhones([
            '12345678901234567', // good
            '+123456789012345678', // good
            '12345678901234567890', // bad
            '+12345678901234567890', // bad
        ]);
    }
}
