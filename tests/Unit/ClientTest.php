<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

use AtolOnline\{Entities\Client,
    Exceptions\AtolEmailTooLongException,
    Exceptions\AtolEmailValidateException,
    Exceptions\AtolInnWrongLengthException,
    Exceptions\AtolNameTooLongException,
    Exceptions\AtolPhoneTooLongException
};

/**
 * Class ClientTest
 */
class ClientTest extends BasicTestCase
{
    /**
     * Тестирует установку параметров
     */
    public function testConstructor()
    {
        $customer = new Client(
            'John Doe',
            '+1/22/99*73s dsdas654 5s6', // +122997365456
            'john@example.com',
            '+fasd3\qe3fs_=nac99013928czc' // 3399013928
        );
        $this->checkAtolEntity($customer);
        $this->assertEquals('John Doe', $customer->getName());
        $this->assertEquals('+122997365456', $customer->getPhone());
        $this->assertEquals('john@example.com', $customer->getEmail());
        $this->assertEquals('3399013928', $customer->getInn());
    }
    
    /**
     * Тестирует исключение о слишком длинном имени
     *
     * @throws \AtolOnline\Exceptions\AtolNameTooLongException
     */
    public function testAtolNameTooLongException()
    {
        $customer = new Client();
        $this->expectException(AtolNameTooLongException::class);
        $customer->setName(self::randomString(257));
    }
    
    /**
     * Тестирует исключение о слишком длинном телефоне
     *
     * @throws \AtolOnline\Exceptions\AtolPhoneTooLongException
     */
    public function testAtolPhoneTooLongException()
    {
        $customer = new Client();
        $this->expectException(AtolPhoneTooLongException::class);
        $customer->setPhone('99999999999999999999999999999999999999999999999999999999999999999999999999');
    }
    
    /**
     * Тестирует исключение о слишком длинной почте
     *
     * @throws \AtolOnline\Exceptions\AtolEmailTooLongException
     * @throws \AtolOnline\Exceptions\AtolEmailValidateException
     */
    public function testAtolEmailTooLongException()
    {
        $customer = new Client();
        $this->expectException(AtolEmailTooLongException::class);
        $customer->setEmail(self::randomString(65));
    }
    
    /**
     * Тестирует исключение о некорректной почте
     *
     * @throws \AtolOnline\Exceptions\AtolEmailTooLongException
     * @throws \AtolOnline\Exceptions\AtolEmailValidateException
     */
    public function testAtolEmailValidateException()
    {
        $customer = new Client();
        $this->expectException(AtolEmailValidateException::class);
        $customer->setEmail(self::randomString(15));
    }
    
    /**
     * Тестирует исключение о некорректной длине ИНН
     *
     * @throws \AtolOnline\Exceptions\AtolInnWrongLengthException
     */
    public function testAtolInnWrongLengthException()
    {
        $company = new Client();
        $this->expectException(AtolInnWrongLengthException::class);
        $company->setInn('123456789');
        $company->setInn('1234567890123');
    }
}