<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Tests\Collections;

use AtolOnline\{
    Collections\Vats,
    Constants\Constraints,
    Entities\Payment,
    Enums\PaymentTypes,
    Exceptions\EmptyVatsException,
    Exceptions\InvalidEntityInCollectionException,
    Exceptions\InvalidEnumValueException,
    Exceptions\NegativePaymentSumException,
    Exceptions\TooHighPaymentSumException,
    Exceptions\TooManyVatsException,
    Tests\BasicTestCase};
use Exception;

/**
 * Набор тестов для проверки работы класса коллекции объектов на примере класса коллекции ставок НДС
 */
class VatsTest extends BasicTestCase
{
    /**
     * Тестирует создание коллекции ставок
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Collections\EntityCollection::checkItemsClasses
     * @covers \AtolOnline\Collections\EntityCollection::jsonSerialize
     * @throws InvalidEnumValueException
     * @throws Exception
     */
    public function testConstructor()
    {
        $vats = new Vats($this->generateVatObjects(3));
        $this->assertIsCollection($vats);
        $this->assertEquals(3, $vats->count());
        $this->assertIsAtolable($vats);
    }

    /**
     * Тестирует выброс исключения при установке нулевого количества ставок
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Collections\EntityCollection::checkItemsClasses
     * @covers \AtolOnline\Collections\EntityCollection::jsonSerialize
     * @covers \AtolOnline\Exceptions\EmptyVatsException
     * @throws InvalidEntityInCollectionException
     */
    public function testEmptyVatsException()
    {
        $this->expectException(EmptyVatsException::class);
        (new Vats([]))->jsonSerialize();
    }

    /**
     * Тестирует выброс исключения при установке слишком большого количества ставок
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Collections\EntityCollection::checkItemsClasses
     * @covers \AtolOnline\Collections\EntityCollection::jsonSerialize
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyVatsException()
    {
        $this->expectException(TooManyVatsException::class);
        (new Vats($this->generateVatObjects(Constraints::MAX_COUNT_DOC_VATS + 1)))->jsonSerialize();
    }

    /**
     * Тестирует выброс исключения при наличии скаляров в коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkItemClass
     * @covers \AtolOnline\Collections\EntityCollection::checkItemsClasses
     * @covers \AtolOnline\Collections\EntityCollection::jsonSerialize
     * @covers \AtolOnline\Exceptions\InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws Exception
     */
    public function testInvalidCollectionItemExceptionScalar(): void
    {
        $this->expectException(InvalidEntityInCollectionException::class);
        $this->expectExceptionMessage("(string)'bad element'");
        (new Vats($this->generateVatObjects(2)))
            ->merge('bad element')
            ->jsonSerialize();
    }

    /**
     * Тестирует выброс исключения при наличии объектов не тех классов в коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection::checkItemClass
     * @covers \AtolOnline\Collections\EntityCollection::checkItemsClasses
     * @covers \AtolOnline\Collections\EntityCollection::jsonSerialize
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws Exception
     */
    public function testInvalidCollectionItemExceptionObject(): void
    {
        $this->expectException(InvalidEntityInCollectionException::class);
        $this->expectExceptionMessage(Payment::class);
        (new Vats($this->generateVatObjects()))
            ->merge([new Payment(PaymentTypes::PREPAID, 1)])
            ->jsonSerialize();
    }
}
