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
    Constraints,
    Entities\Payment,
    Enums\PaymentType,
    Tests\BasicTestCase};
use AtolOnline\Exceptions\{
    NegativePaymentSumException,
    TooHighPaymentSumException,};
use Exception;

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
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws Exception
     */
    public function testConstructor(): void
    {
        $this->assertIsAtolable(
            new Payment(PaymentType::ELECTRON, 123.456789),
            [
                'type' => PaymentType::ELECTRON,
                'sum' => 123.46,
            ]
        );
    }

    /**
     * Тестирует исключение при слишком большой сумме
     *
     * @covers \AtolOnline\Entities\Payment
     * @covers \AtolOnline\Entities\Payment::setSum
     * @covers \AtolOnline\Exceptions\TooHighPaymentSumException
     * @return void
     * @throws NegativePaymentSumException
     */
    public function testTooHighPaymentSumException(): void
    {
        $this->expectException(TooHighPaymentSumException::class);
        new Payment(PaymentType::ELECTRON, Constraints::MAX_COUNT_PAYMENT_SUM + 1);
    }

    /**
     * Тестирует исключение при отрицательной сумме
     *
     * @covers \AtolOnline\Entities\Payment
     * @covers \AtolOnline\Entities\Payment::setSum
     * @covers \AtolOnline\Exceptions\NegativePaymentSumException
     * @return void
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function testNegativePaymentSumException(): void
    {
        $this->expectException(NegativePaymentSumException::class);
        new Payment(PaymentType::ELECTRON, -1);
    }
}
