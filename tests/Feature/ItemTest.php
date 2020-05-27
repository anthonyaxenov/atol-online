<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

use AtolOnline\{Constants\PaymentMethods,
    Constants\PaymentObjects,
    Constants\VatTypes,
    Entities\Item,
    Exceptions\AtolNameTooLongException,
    Exceptions\AtolPriceTooHighException,
    Exceptions\AtolTooManyException,
    Exceptions\AtolUnitTooLongException,
    Exceptions\AtolUserdataTooLongException};

/**
 * Class ItemTest
 */
class ItemTest extends BasicTestCase
{
    /**
     * Тестирует установку параметров через конструктор
     *
     * @throws AtolOnline\Exceptions\AtolNameTooLongException
     * @throws AtolOnline\Exceptions\AtolPriceTooHighException
     * @throws AtolOnline\Exceptions\AtolTooManyException
     * @throws AtolOnline\Exceptions\AtolUnitTooLongException
     */
    public function testConstructor()
    {
        $item = new Item(
            'Банан',
            65.99,
            2.74,
            'кг',
            VatTypes::NONE,
            PaymentObjects::COMMODITY,
            PaymentMethods::FULL_PAYMENT
        );
        $this->checkAtolEntity($item);
        $this->assertEquals('Банан', $item->getName());
        $this->assertEquals(65.99, $item->getPrice());
        $this->assertEquals(2.74, $item->getQuantity());
        $this->assertEquals('кг', $item->getMeasurementUnit());
        $this->assertEquals(VatTypes::NONE, $item->getVat()->getType());
        $this->assertEquals(PaymentObjects::COMMODITY, $item->getPaymentObject());
        $this->assertEquals(PaymentMethods::FULL_PAYMENT, $item->getPaymentMethod());
    }
    
    /**
     * Тестирует установку параметров через сеттеры
     *
     * @throws AtolOnline\Exceptions\AtolNameTooLongException
     * @throws AtolOnline\Exceptions\AtolPriceTooHighException
     * @throws AtolOnline\Exceptions\AtolTooManyException
     * @throws AtolOnline\Exceptions\AtolUnitTooLongException
     * @throws AtolOnline\Exceptions\AtolUserdataTooLongException
     */
    public function testSetters()
    {
        $item = new Item();
        $item->setName('Банан');
        $item->setPrice(65.99);
        $item->setQuantity(2.74);
        $item->setMeasurementUnit('кг');
        $item->setVatType(VatTypes::NONE);
        $item->setPaymentObject(PaymentObjects::COMMODITY);
        $item->setPaymentMethod(PaymentMethods::FULL_PAYMENT);
        $item->setUserData('Some user data');
        $this->checkAtolEntity($item);
        $this->assertEquals('Банан', $item->getName());
        $this->assertEquals(65.99, $item->getPrice());
        $this->assertEquals(2.74, $item->getQuantity());
        $this->assertEquals('кг', $item->getMeasurementUnit());
        $this->assertEquals(VatTypes::NONE, $item->getVat()->getType());
        $this->assertEquals(PaymentObjects::COMMODITY, $item->getPaymentObject());
        $this->assertEquals(PaymentMethods::FULL_PAYMENT, $item->getPaymentMethod());
        $this->assertEquals('Some user data', $item->getUserData());
    }
    
    /**
     * Тестирует установку ставки НДС разными путями
     *
     * @throws AtolOnline\Exceptions\AtolNameTooLongException
     * @throws AtolOnline\Exceptions\AtolPriceTooHighException
     * @throws AtolOnline\Exceptions\AtolTooManyException
     * @throws AtolOnline\Exceptions\AtolUnitTooLongException
     */
    public function testSetVat()
    {
        $item = new Item();
        $item->setVatType(VatTypes::NONE);
        $this->assertEquals(VatTypes::NONE, $item->getVat()->getType());
        $item->setVatType(VatTypes::VAT20);
        $this->assertEquals(VatTypes::VAT20, $item->getVat()->getType());
    }
    
    /**
     * Тестирует исключение о слишком длинном наименовании
     *
     * @throws AtolOnline\Exceptions\AtolNameTooLongException
     * @throws AtolOnline\Exceptions\AtolPriceTooHighException
     * @throws AtolOnline\Exceptions\AtolTooManyException
     * @throws AtolOnline\Exceptions\AtolUnitTooLongException
     */
    public function testAtolNameTooLongException()
    {
        $item = new Item();
        $this->expectException(AtolNameTooLongException::class);
        $item->setName('Банан Банан Банан Банан Банан Банан Банан Банан Банан Банан Банан Банан');
    }
    
    /**
     * Тестирует исключение о слишком высоком количестве
     *
     * @throws AtolOnline\Exceptions\AtolNameTooLongException
     * @throws AtolOnline\Exceptions\AtolTooManyException
     * @throws AtolOnline\Exceptions\AtolPriceTooHighException
     * @throws AtolOnline\Exceptions\AtolUnitTooLongException
     */
    public function testAtolQuantityTooHighException()
    {
        $item = new Item();
        $this->expectException(AtolTooManyException::class);
        $item->setQuantity(100000.1);
    }
    
    /**
     * Тестирует исключение о слишком высокой цене
     *
     * @throws AtolOnline\Exceptions\AtolPriceTooHighException
     * @throws AtolOnline\Exceptions\AtolNameTooLongException
     * @throws AtolOnline\Exceptions\AtolTooManyException
     * @throws AtolOnline\Exceptions\AtolUnitTooLongException
     */
    public function testAtolPriceTooHighException()
    {
        $item = new Item();
        $this->expectException(AtolPriceTooHighException::class);
        $item->setPrice(42949673.1);
    }
    
    /**
     * Тестирует исключение о слишком длинных польз. данных
     *
     * @throws AtolOnline\Exceptions\AtolUserdataTooLongException
     * @throws AtolOnline\Exceptions\AtolPriceTooHighException
     * @throws AtolOnline\Exceptions\AtolNameTooLongException
     * @throws AtolOnline\Exceptions\AtolTooManyException
     * @throws AtolOnline\Exceptions\AtolUnitTooLongException
     */
    public function testAtolUserdataTooLongException()
    {
        $item = new Item();
        $this->expectException(AtolUserdataTooLongException::class);
        $item->setUserData('User data User data User data User data User data User data User data');
    }
    
    /**
     * Тестирует исключение о слишком длинной единице измерения
     *
     * @throws AtolOnline\Exceptions\AtolNameTooLongException
     * @throws AtolOnline\Exceptions\AtolPriceTooHighException
     * @throws AtolOnline\Exceptions\AtolTooManyException
     * @throws AtolOnline\Exceptions\AtolUnitTooLongException
     */
    public function testAtolUnitTooLongException()
    {
        $item = new Item();
        $this->expectException(AtolUnitTooLongException::class);
        $item->setMeasurementUnit('кг кг кг кг кг кг кг кг кг ');
    }
}