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
    Entities\Company,
    Enums\SnoType,
    Exceptions\InvalidEmailException,
    Exceptions\InvalidInnLengthException,
    Exceptions\InvalidPaymentAddressException,
    Exceptions\TooLongEmailException,
    Exceptions\TooLongPaymentAddressException,
    Helpers,
    Tests\BasicTestCase};
use Exception;

/**
 * Набор тестов для проверки работы класса продавца
 */
class CompanyTest extends BasicTestCase
{
    /**
     * Тестирует конструктор с сеттерами и приведение к json с геттерами
     *
     * @covers \AtolOnline\Entities\Company
     * @covers \AtolOnline\Entities\Company::jsonSerialize
     * @covers \AtolOnline\Entities\Company::setEmail
     * @covers \AtolOnline\Entities\Company::setSno
     * @covers \AtolOnline\Entities\Company::setInn
     * @covers \AtolOnline\Entities\Company::setPaymentAddress
     * @covers \AtolOnline\Entities\Company::getEmail
     * @covers \AtolOnline\Entities\Company::getSno
     * @covers \AtolOnline\Entities\Company::getInn
     * @covers \AtolOnline\Entities\Company::getPaymentAddress
     * @throws Exception
     */
    public function testConstructor()
    {
        $this->assertIsAtolable(
            new Company(
                $inn = '1234567890',
                $sno = SnoType::OSN,
                $paymentAddress = 'https://example.com',
                $email = 'company@example.com',
            ),
            [
                'inn' => $inn,
                'sno' => $sno,
                'payment_address' => $paymentAddress,
                'email' => $email,
            ]
        );
    }

    /**
     * Тестирует исключение о слишком длинном email
     *
     * @covers \AtolOnline\Entities\Company
     * @covers \AtolOnline\Entities\Company::setEmail
     * @covers \AtolOnline\Exceptions\TooLongEmailException
     * @throws InvalidEmailException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws TooLongEmailException
     * @throws TooLongPaymentAddressException
     */
    public function testEmailTooLongException()
    {
        $this->expectException(TooLongEmailException::class);
        new Company('1234567890', SnoType::OSN, 'https://example.com', Helpers::randomStr(65));
    }

    /**
     * Тестирует исключение о невалидном email
     *
     * @covers \AtolOnline\Entities\Company
     * @covers \AtolOnline\Entities\Company::setEmail
     * @covers \AtolOnline\Exceptions\InvalidEmailException
     */
    public function testInvalidEmailException()
    {
        $this->expectException(InvalidEmailException::class);
        new Company('1234567890', SnoType::OSN, 'https://example.com', 'company@examas%^*.com');
    }

    /**
     * Тестирует исключение о слишком длинном платёжном адресе
     *
     * @covers \AtolOnline\Entities\Company
     * @covers \AtolOnline\Entities\Company::setInn
     * @covers \AtolOnline\Exceptions\InvalidInnLengthException
     * @throws InvalidEmailException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws TooLongEmailException
     * @throws TooLongPaymentAddressException
     */
    public function testInvalidInnLengthException()
    {
        $this->expectException(InvalidInnLengthException::class);
        new Company(Helpers::randomStr(13), SnoType::OSN, 'https://example.com', 'company@example.com');
    }

    /**
     * Тестирует исключение о слишком длинном платёжном адресе
     *
     * @covers \AtolOnline\Entities\Company
     * @covers \AtolOnline\Entities\Company::setPaymentAddress
     * @covers \AtolOnline\Exceptions\TooLongPaymentAddressException
     * @throws InvalidEmailException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws TooLongEmailException
     * @throws TooLongPaymentAddressException
     */
    public function testTooLongPaymentAddressException()
    {
        $this->expectException(TooLongPaymentAddressException::class);
        new Company('1234567890', SnoType::OSN, Helpers::randomStr(257), 'company@example.com');
    }

    /**
     * Тестирует исключение о невалидном платёжном адресе
     *
     * @covers \AtolOnline\Entities\Company
     * @covers \AtolOnline\Entities\Company::setPaymentAddress
     * @covers \AtolOnline\Exceptions\InvalidPaymentAddressException
     */
    public function testInvalidPaymentAddressException()
    {
        $this->expectException(InvalidPaymentAddressException::class);
        new Company('1234567890', SnoType::OSN, '', 'company@example.com');
    }
}
