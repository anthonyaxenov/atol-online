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
    Exceptions\EmptyPaymentsException,
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
     * Тестирует выброс исключения при установке слишком большого количества оплат
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
        (new Payments($this->generatePaymentObjects(Constraints::MAX_COUNT_DOC_PAYMENTS + 1)))->jsonSerialize();
    }

    /**
     * Тестирует выброс исключения при установке нулевого количества оплат
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Collections\EntityCollection::jsonSerialize
     * @covers \AtolOnline\Exceptions\EmptyPaymentsException
     * @throws InvalidEntityInCollectionException
     */
    public function testEmptyPaymentsException()
    {
        $this->expectException(EmptyPaymentsException::class);
        (new Payments([]))->jsonSerialize();
    }
}
