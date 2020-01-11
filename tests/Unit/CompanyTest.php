<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

use AtolOnline\{Constants\SnoTypes,
    Entities\Company,
    Exceptions\AtolEmailTooLongException,
    Exceptions\AtolEmailValidateException,
    Exceptions\AtolInnWrongLengthException,
    Exceptions\AtolPaymentAddressTooLongException
};

/**
 * Class CompanyTest
 */
class CompanyTest extends BasicTestCase
{
    /**
     * Тестирует установку параметров через конструктор
     *
     * @throws AtolOnline\Exceptions\AtolEmailTooLongException
     * @throws AtolOnline\Exceptions\AtolEmailValidateException
     * @throws AtolOnline\Exceptions\AtolInnWrongLengthException
     * @throws AtolOnline\Exceptions\AtolPaymentAddressTooLongException
     */
    public function testConstructor()
    {
        $company = new Company(
            SnoTypes::OSN,
            '5544332219',
            'https://v4.online.atol.ru',
            'company@example.com'
        );
        $this->checkAtolEntity($company);
        $this->assertEquals(SnoTypes::OSN, $company->getSno());
        $this->assertEquals('5544332219', $company->getInn());
        $this->assertEquals('https://v4.online.atol.ru', $company->getPaymentAddress());
        $this->assertEquals('company@example.com', $company->getEmail());
    }
    
    /**
     * Тестирует исключение о некорректной длине ИНН
     *
     * @throws AtolOnline\Exceptions\AtolInnWrongLengthException
     * @throws AtolOnline\Exceptions\AtolEmailTooLongException
     * @throws AtolOnline\Exceptions\AtolEmailValidateException
     * @throws AtolOnline\Exceptions\AtolPaymentAddressTooLongException
     */
    public function testAtolInnWrongLengthException()
    {
        $company = new Company();
        $this->expectException(AtolInnWrongLengthException::class);
        $company->setInn('321');
        $company->setInn('1234567890123');
    }
    
    /**
     * Тестирует исключение о слишком длинном платёжном адресе
     *
     * @throws AtolOnline\Exceptions\AtolPaymentAddressTooLongException
     * @throws AtolOnline\Exceptions\AtolEmailTooLongException
     * @throws AtolOnline\Exceptions\AtolEmailValidateException
     * @throws AtolOnline\Exceptions\AtolInnWrongLengthException
     */
    public function testAtolPaymentAddressTooLongException()
    {
        $company = new Company();
        $this->expectException(AtolPaymentAddressTooLongException::class);
        $company->setPaymentAddress('John Doe John Doe John Doe John Doe '.
            'John Doe John Doe John Doe John Doe John Doe John Doe John Doe John Doe John '.
            'Doe John Doe John Doe John Doe John DoeJohn Doe John Doe John Doe John Doe '.
            'John Doe John Doe John Doe John Doe John Doe John Doe John Doe John Doe John '.
            'Doe John Doe John Doe John Doe John Doe');
    }
    
    /**
     * Тестирует исключение о слишком длинной почте
     *
     * @throws AtolOnline\Exceptions\AtolEmailTooLongException
     * @throws AtolOnline\Exceptions\AtolEmailValidateException
     * @throws AtolOnline\Exceptions\AtolInnWrongLengthException
     * @throws AtolOnline\Exceptions\AtolPaymentAddressTooLongException
     */
    public function testAtolEmailTooLongException()
    {
        $company = new Company();
        $this->expectException(AtolEmailTooLongException::class);
        $company->setEmail('johnjohnjohnjohnjohnjohndoedoedoedoe@exampleexampleexampleexample.com');
    }
    
    /**
     * Тестирует исключение о некорректной почте
     *
     * @throws AtolOnline\Exceptions\AtolEmailValidateException
     * @throws AtolOnline\Exceptions\AtolEmailTooLongException
     * @throws AtolOnline\Exceptions\AtolInnWrongLengthException
     * @throws AtolOnline\Exceptions\AtolPaymentAddressTooLongException
     */
    public function testAtolEmailValidateException()
    {
        $company = new Company();
        $this->expectException(AtolEmailValidateException::class);
        $company->setEmail('John Doe');
    }
}