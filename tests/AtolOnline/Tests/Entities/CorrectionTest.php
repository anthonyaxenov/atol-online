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
    Helpers,
    Tests\BasicTestCase};
use AtolOnline\Exceptions\{
    EmptyCorrectionNumberException,
    InvalidCorrectionDateException,
    InvalidEntityInCollectionException,
    InvalidEnumValueException,
    NegativePaymentSumException,
    TooHighPaymentSumException,
    TooLongCashierException};
use Exception;

/**
 * Набор тестов для проверки работы класса чека коррекции
 */
class CorrectionTest extends BasicTestCase
{
    /**
     * Тестирует конструктор и корректное приведение к json
     *
     * @return void
     * @covers \AtolOnline\Entities\Correction
     * @covers \AtolOnline\Entities\Correction::setCompany
     * @covers \AtolOnline\Entities\Correction::setCorrectionInfo
     * @covers \AtolOnline\Entities\Correction::setPayments
     * @covers \AtolOnline\Entities\Correction::setVats
     * @covers \AtolOnline\Entities\Correction::jsonSerialize
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws Exception
     */
    public function testConstructor(): void
    {
        $correction = $this->newCorrection();
        $this->assertIsAtolable($correction);
    }

    /**
     * Тестирует установку валидного кассира
     *
     * @return void
     * @covers \AtolOnline\Entities\Correction::setCashier
     * @covers \AtolOnline\Entities\Correction::getCashier
     * @covers \AtolOnline\Entities\Correction::jsonSerialize
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws TooLongCashierException
     */
    public function testCashier(): void
    {
        $correction = $this->newCorrection()->setCashier(Helpers::randomStr());
        $this->assertArrayHasKey('cashier', $correction->jsonSerialize());
        $this->assertSame($correction->getCashier(), $correction->jsonSerialize()['cashier']);
    }

    /**
     * Тестирует обнуление кассира
     *
     * @param mixed $param
     * @return void
     * @dataProvider providerNullableStrings
     * @covers       \AtolOnline\Entities\Correction::setCashier
     * @covers       \AtolOnline\Entities\Correction::getCashier
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws TooLongCashierException
     */
    public function testNullableCashier(mixed $param): void
    {
        $this->assertNull($this->newCorrection()->setCashier($param)->getCashier());
    }

    /**
     * Тестирует выброс исключения при установке слишком длинного кассира (лол)
     *
     * @return void
     * @covers \AtolOnline\Entities\Correction::setCashier
     * @covers \AtolOnline\Exceptions\TooLongCashierException
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws TooLongCashierException
     */
    public function testTooLongCashierException(): void
    {
        $this->expectException(TooLongCashierException::class);
        $this->newCorrection()->setCashier(Helpers::randomStr(Constraints::MAX_LENGTH_CASHIER_NAME + 1));
    }
}
