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
     * Тестирует выброс исключения при установке слишком большого количества ставок через конструктор
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyVatsExceptionByConstructor()
    {
        $this->expectException(TooManyVatsException::class);
        new Vats($this->generateVatObjects(Constraints::MAX_COUNT_DOC_VATS + 1));
    }

    /**
     * Тестирует добавление ставки в начало коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::prepend
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     */
    public function testPrepend()
    {
        $vats = (new Vats($this->generateVatObjects(3)))
            ->prepend($this->generateVatObjects());
        $this->assertEquals(4, $vats->count());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в начало коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::prepend
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyVatsExceptionByPrepend()
    {
        $this->expectException(TooManyVatsException::class);
        (new Vats($this->generateVatObjects(Constraints::MAX_COUNT_DOC_VATS)))
            ->prepend($this->generateVatObjects());
    }

    /**
     * Тестирует добавление ставки в конец коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::add
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     */
    public function testAdd()
    {
        $vats = (new Vats($this->generateVatObjects(3)))
            ->add($this->generateVatObjects());
        $this->assertEquals(4, $vats->count());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в конец коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::add
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyVatsExceptionByAdd()
    {
        $this->expectException(TooManyVatsException::class);
        (new Vats($this->generateVatObjects(Constraints::MAX_COUNT_DOC_VATS)))
            ->add($this->generateVatObjects());
    }

    /**
     * Тестирует добавление лишних ставок в конец коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::push
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     */
    public function testPush()
    {
        $vats = (new Vats($this->generateVatObjects(3)))
            ->push(...$this->generateVatObjects(3));
        $this->assertEquals(6, $vats->count());
    }

    /**
     * Тестирует выброс исключения при добавлении лишних ставок в конец коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::push
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyVatsExceptionByPush()
    {
        $this->expectException(TooManyVatsException::class);
        (new Vats($this->generateVatObjects(Constraints::MAX_COUNT_DOC_VATS)))
            ->push(...$this->generateVatObjects());
    }

    /**
     * Тестирует добавление ставки в начало коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::merge
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     */
    public function testMerge()
    {
        $vats = (new Vats($this->generateVatObjects(3)))
            ->merge($this->generateVatObjects(3));
        $this->assertEquals(6, $vats->count());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в начало коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::merge
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyVatsExceptionByMerge()
    {
        $this->expectException(TooManyVatsException::class);
        (new Vats($this->generateVatObjects(Constraints::MAX_COUNT_DOC_VATS - 1)))
            ->merge($this->generateVatObjects(2));
    }

    /**
     * Тестирует выброс исключения при наличии скаляров в коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkItemClass
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
