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
    Entities\Payment,
    Enums\PaymentTypes,
    Exceptions\InvalidEnumValueException,
    Exceptions\NegativePaymentSumException,
    Exceptions\TooHighPaymentSumException,
    Exceptions\TooManyPaymentsException,
    Tests\BasicTestCase};
use Exception;

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
     */
    public function testTooManyPaymentsExceptionByConstructor()
    {
        $this->expectException(TooManyPaymentsException::class);
        new Payments($this->generateObjects(Constraints::MAX_COUNT_DOC_PAYMENTS + 1));
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
     */
    public function testTooManyPaymentsExceptionByPrepend()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generateObjects(Constraints::MAX_COUNT_DOC_PAYMENTS)))
            ->prepend($this->generateObjects());
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
     */
    public function testTooManyPaymentsExceptionByAdd()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generateObjects(Constraints::MAX_COUNT_DOC_PAYMENTS)))
            ->add($this->generateObjects());
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
     */
    public function testTooManyPaymentsExceptionByPush()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generateObjects(Constraints::MAX_COUNT_DOC_PAYMENTS + 1)))
            ->push(...$this->generateObjects());
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
     */
    public function testTooManyPaymentsExceptionByMerge()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generateObjects(Constraints::MAX_COUNT_DOC_PAYMENTS - 1)))
            ->merge($this->generateObjects(2));
    }

    /**
     * Генерирует массив тестовых объектов оплаты
     *
     * @param int $count
     * @return Payment[]
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws Exception
     */
    protected function generateObjects(int $count = 1): array
    {
        $types = PaymentTypes::toArray();
        $result = [];
        for ($i = 0; $i < abs($count); ++$i) {
            $result[] = new Payment(
                array_values($types)[random_int(min($types), max($types))],
                random_int(1, 100) * 2 / 3
            );
        }
        return $result;
    }
}
