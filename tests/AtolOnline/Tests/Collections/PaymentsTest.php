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
    Collections\Payments,
    Constants\Constraints,
    Exceptions\InvalidEntityInCollectionException,
    Exceptions\InvalidEnumValueException,
    Exceptions\NegativePaymentSumException,
    Exceptions\TooHighPaymentSumException,
    Exceptions\TooManyPaymentsException,
    Tests\BasicTestCase};

/**
 * Набор тестов для проверки работы класса коллекции оплат
 */
class PaymentsTest extends BasicTestCase
{
    /**
     * Тестирует выброс исключения при установке слишком большого количества оплат через конструктор
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyPaymentsException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyPaymentsExceptionByConstructor()
    {
        $this->expectException(TooManyPaymentsException::class);
        new Payments($this->generatePaymentObjects(Constraints::MAX_COUNT_DOC_PAYMENTS + 1));
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в начало коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection::prepend
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyPaymentsException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyPaymentsExceptionByPrepend()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generatePaymentObjects(Constraints::MAX_COUNT_DOC_PAYMENTS)))
            ->prepend($this->generatePaymentObjects());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в конец коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::add
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyPaymentsException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyPaymentsExceptionByAdd()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generatePaymentObjects(Constraints::MAX_COUNT_DOC_PAYMENTS)))
            ->add($this->generatePaymentObjects());
    }

    /**
     * Тестирует выброс исключения при добавлении лишних оплат в конец коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::push
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyPaymentsException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyPaymentsExceptionByPush()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generatePaymentObjects(Constraints::MAX_COUNT_DOC_PAYMENTS + 1)))
            ->push(...$this->generatePaymentObjects());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в начало коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::merge
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyPaymentsException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyPaymentsExceptionByMerge()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generatePaymentObjects(Constraints::MAX_COUNT_DOC_PAYMENTS - 1)))
            ->merge($this->generatePaymentObjects(2));
    }
}
