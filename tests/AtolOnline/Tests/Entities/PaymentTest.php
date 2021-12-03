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
    Enums\PaymentTypes,
    Tests\BasicTestCase
};
use AtolOnline\Exceptions\{
    InvalidEnumValueException,
    NegativePaymentSumException,
    TooHighPaymentSumException,
};

/**
 * Набор тестов для проверки работы класса оплаты
 */
class PaymentTest extends BasicTestCase
{
    /**
     * Тестирует конструктор
     *
     * @covers \AtolOnline\Entities\Payment
     * @covers \AtolOnline\Entities\Payment::setType
     * @covers \AtolOnline\Entities\Payment::getType
     * @covers \AtolOnline\Entities\Payment::setSum
     * @covers \AtolOnline\Entities\Payment::getSum
     * @covers \AtolOnline\Entities\Payment::jsonSerialize
     * @return void
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function testConstructor(): void
    {
        $this->assertAtolable(
            new Payment(PaymentTypes::ELECTRON, 123.456789),
            [
                'type' => PaymentTypes::ELECTRON,
                'sum' => 123.46,
            ]
        );
    }

    /**
     * Тестирует исключение при некорректном типе
     *
     * @covers \AtolOnline\Entities\Payment
     * @covers \AtolOnline\Entities\Payment::setType
     * @covers \AtolOnline\Enums\PaymentTypes::isValid
     * @covers \AtolOnline\Exceptions\InvalidEnumValueException
     * @return void
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function testInvalidEnumValueException(): void
    {
        $this->expectException(InvalidEnumValueException::class);
        $this->expectExceptionMessage('Некорректное значение AtolOnline\Enums\PaymentTypes::123');
        new Payment(123, 123.456789);
    }

    /**
     * Тестирует исключение при слишком большой сумме
     *
     * @covers \AtolOnline\Entities\Payment
     * @covers \AtolOnline\Entities\Payment::setSum
     * @covers \AtolOnline\Exceptions\TooHighPaymentSumException
     * @return void
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     */
    public function testTooHighPaymentSumException(): void
    {
        $this->expectException(TooHighPaymentSumException::class);
        new Payment(PaymentTypes::ELECTRON, Constraints::MAX_COUNT_PAYMENT_SUM + 1);
    }

    /**
     * Тестирует исключение при отрицательной сумме
     *
     * @covers \AtolOnline\Entities\Payment
     * @covers \AtolOnline\Entities\Payment::setSum
     * @covers \AtolOnline\Exceptions\NegativePaymentSumException
     * @return void
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function testNegativePaymentSumException(): void
    {
        $this->expectException(NegativePaymentSumException::class);
        new Payment(PaymentTypes::ELECTRON, -1);
    }
}
