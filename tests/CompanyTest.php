<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnlineTests;

use AtolOnline\{
    Entities\Company,
    Enums\SnoTypes,
    Exceptions\InvalidEmailException,
    Exceptions\InvalidEnumValueException,
    Exceptions\InvalidInnLengthException,
    Exceptions\InvalidPaymentAddressException,
    Exceptions\TooLongEmailException,
    Exceptions\TooLongPaymentAddressException,
    Helpers};

/**
 * Набор тестов для проверки работы класс продавца
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
     */
    public function testConstructor()
    {
        $this->assertAtolable(new Company(
            $email = 'company@example.com',
            $sno = SnoTypes::OSN,
            $inn = '1234567890',
            $payment_address = 'https://example.com',
        ), [
            'email' => $email,
            'sno' => $sno,
            'inn' => $inn,
            'payment_address' => $payment_address,
        ]);
    }

    /**
     * Тестирует исключение о слишком длинном email
     *
     * @covers \AtolOnline\Entities\Company
     * @covers \AtolOnline\Entities\Company::setEmail
     * @covers \AtolOnline\Exceptions\TooLongEmailException
     */
    public function testEmailTooLongException()
    {
        $this->expectException(TooLongEmailException::class);
        new Company(Helpers::randomStr(65), SnoTypes::OSN, '1234567890', 'https://example.com');
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
        new Company('company@examas%^*.com', SnoTypes::OSN, '1234567890', 'https://example.com');
    }

    /**
     * Тестирует исключение о слишком длинном платёжном адресе
     *
     * @covers \AtolOnline\Entities\Company
     * @covers \AtolOnline\Entities\Company::setSno
     * @covers \AtolOnline\Exceptions\InvalidEnumValueException
     */
    public function testInvalidSnoException()
    {
        $this->expectException(InvalidEnumValueException::class);
        new Company('company@example.com', 'test', '1234567890', 'https://example.com');
    }

    /**
     * Тестирует исключение о слишком длинном платёжном адресе
     *
     * @covers \AtolOnline\Entities\Company
     * @covers \AtolOnline\Entities\Company::setInn
     * @covers \AtolOnline\Exceptions\InvalidInnLengthException
     */
    public function testInvalidInnLengthException()
    {
        $this->expectException(InvalidInnLengthException::class);
        new Company('company@example.com', SnoTypes::OSN, Helpers::randomStr(13), 'https://example.com');
    }

    /**
     * Тестирует исключение о слишком длинном платёжном адресе
     *
     * @covers \AtolOnline\Entities\Company
     * @covers \AtolOnline\Entities\Company::setPaymentAddress
     * @covers \AtolOnline\Exceptions\TooLongPaymentAddressException
     */
    public function testTooLongPaymentAddressException()
    {
        $this->expectException(TooLongPaymentAddressException::class);
        new Company('company@example.com', SnoTypes::OSN, '1234567890', Helpers::randomStr(257));
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
        new Company('company@example.com', SnoTypes::OSN, '1234567890', '');
    }
}