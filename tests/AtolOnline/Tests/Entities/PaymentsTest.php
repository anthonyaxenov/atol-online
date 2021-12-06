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
    Constants\Constraints,
    Entities\Payment,
    Entities\Payments,
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
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
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
     * @covers \AtolOnline\Entities\EntityCollection::prepend
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyPaymentsException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function testTooManyPaymentsExceptionByPrepend()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generateObjects(10)))
            ->prepend($this->generateObjects());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в конец коллекции
     *
     * @covers \AtolOnline\Entities\Payments
     * @covers \AtolOnline\Entities\Payments::add
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyPaymentsException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function testTooManyPaymentsExceptionByAdd()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generateObjects(10)))
            ->add($this->generateObjects());
    }

    /**
     * Тестирует выброс исключения при добавлении лишних оплат в конец коллекции
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::push
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyPaymentsException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function testTooManyPaymentsExceptionByPush()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generateObjects(10)))
            ->push(...$this->generateObjects());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в начало коллекции
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::merge
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyPaymentsException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function testTooManyPaymentsExceptionByMerge()
    {
        $this->expectException(TooManyPaymentsException::class);
        (new Payments($this->generateObjects(9)))
            ->merge($this->generateObjects(2));
    }

    /**
     * Генерирует массив тестовых объектов оплаты
     *
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
