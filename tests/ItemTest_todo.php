<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Tests;

use AtolOnline\{
    Constants\PaymentMethods,
    Constants\PaymentObjects,
    Constants\VatTypes,
    Entities\Item,
    Exceptions\BasicTooManyException,
    Exceptions\TooHighPriceException,
    Exceptions\TooLongNameException,
    Exceptions\TooLongUnitException,
    Exceptions\TooLongUserdataException,};

/**
 * Class ItemTest
 */
class ItemTestTodo extends BasicTestCase
{
    /**
     * Тестирует установку параметров через конструктор
     *
     * @throws AtolOnline\Exceptions\TooLongNameException
     * @throws AtolOnline\Exceptions\TooHighPriceException
     * @throws AtolOnline\Exceptions\BasicTooManyException
     * @throws AtolOnline\Exceptions\TooLongUnitException
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
        $this->assertAtolable($item);
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
     * @throws AtolOnline\Exceptions\TooLongNameException
     * @throws AtolOnline\Exceptions\TooHighPriceException
     * @throws AtolOnline\Exceptions\BasicTooManyException
     * @throws AtolOnline\Exceptions\TooLongUnitException
     * @throws AtolOnline\Exceptions\TooLongUserdataException
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
        $this->assertAtolable($item);
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
     * @throws TooHighPriceException
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
     * @throws TooLongNameException
     */
    public function testAtolNameTooLongException()
    {
        $item = new Item();
        $this->expectException(TooLongNameException::class);
        $item->setName(Helpers::randomStr(130));
    }

    /**
     * Тестирует исключение о слишком высоком количестве
     *
     * @throws TooHighPriceException
     * @throws BasicTooManyException
     * @throws TooLongUnitException
     */
    public function testAtolQuantityTooHighException()
    {
        $item = new Item();
        $this->expectException(BasicTooManyException::class);
        $item->setQuantity(100000.1);
    }

    /**
     * Тестирует исключение о слишком высокой цене
     *
     * @throws TooHighPriceException
     */
    public function testAtolPriceTooHighException()
    {
        $item = new Item();
        $this->expectException(TooHighPriceException::class);
        $item->setPrice(42949673.1);
    }

    /**
     * Тестирует исключение о слишком длинных польз. данных
     *
     * @throws TooLongUserdataException
     */
    public function testAtolUserdataTooLongException()
    {
        $item = new Item();
        $this->expectException(TooLongUserdataException::class);
        $item->setUserData('User data User data User data User data User data User data User data');
    }

    /**
     * Тестирует исключение о слишком длинной единице измерения
     *
     * @throws TooLongUnitException
     */
    public function testAtolUnitTooLongException()
    {
        $item = new Item();
        $this->expectException(TooLongUnitException::class);
        $item->setMeasurementUnit('кг кг кг кг кг кг кг кг кг ');
    }
}