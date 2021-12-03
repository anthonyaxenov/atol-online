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
    Entities\AgentInfo,
    Entities\Item,
    Entities\MoneyTransferOperator,
    Entities\PayingAgent,
    Entities\ReceivePaymentsOperator,
    Entities\Supplier,
    Entities\Vat,
    Enums\AgentTypes,
    Enums\PaymentMethods,
    Enums\PaymentObjects,
    Enums\VatTypes,
    Exceptions\EmptyItemNameException,
    Exceptions\InvalidDeclarationNumberException,
    Exceptions\InvalidEnumValueException,
    Exceptions\InvalidInnLengthException,
    Exceptions\InvalidOKSMCodeException,
    Exceptions\InvalidPhoneException,
    Exceptions\NegativeItemExciseException,
    Exceptions\NegativeItemPriceException,
    Exceptions\NegativeItemQuantityException,
    Exceptions\TooHighItemQuantityException,
    Exceptions\TooHighPriceException,
    Exceptions\TooHighSumException,
    Exceptions\TooLongItemCodeException,
    Exceptions\TooLongItemNameException,
    Exceptions\TooLongMeasurementUnitException,
    Exceptions\TooLongPayingAgentOperationException,
    Exceptions\TooLongUserdataException,
    Exceptions\TooManyException,
    Helpers,
    Tests\BasicTestCase
};

/**
 * Набор тестов для проверки работы класс продавца
 */
class ItemTest extends BasicTestCase
{
    /**
     * Тестирует конструктор с сеттерами и приведение к json с геттерами
     *
     * @covers \AtolOnline\Entities\Item
     * @covers \AtolOnline\Entities\Item::setName
     * @covers \AtolOnline\Entities\Item::getName
     * @covers \AtolOnline\Entities\Item::setPrice
     * @covers \AtolOnline\Entities\Item::getPrice
     * @covers \AtolOnline\Entities\Item::setQuantity
     * @covers \AtolOnline\Entities\Item::getQuantity
     * @covers \AtolOnline\Entities\Item::getSum
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws TooLongItemNameException
     * @throws TooHighPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     * @throws NegativeItemQuantityException
     */
    public function testConstructor(): void
    {
        $this->assertAtolable(
            new Item('test item', 2, 3),
            [
                'name' => 'test item',
                'price' => 2,
                'quantity' => 3,
                'sum' => 6,
            ]
        );
    }

    /**
     * Тестирует выброс исключения при установке слишком длинного имени предмета расчёта
     *
     * @covers \AtolOnline\Entities\Item
     * @covers \AtolOnline\Entities\Item::setName
     * @covers \AtolOnline\Exceptions\TooLongItemNameException
     * @throws TooLongItemNameException
     * @throws TooHighPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     * @throws NegativeItemQuantityException
     */
    public function testTooLongItemNameException(): void
    {
        $this->expectException(TooLongItemNameException::class);
        new Item(Helpers::randomStr(Constraints::MAX_LENGTH_ITEM_NAME + 1), 2, 3);
    }

    /**
     * Тестирует выброс исключения при установке пустого имени предмета расчёта
     *
     * @covers \AtolOnline\Entities\Item
     * @covers \AtolOnline\Entities\Item::setName
     * @covers \AtolOnline\Exceptions\EmptyItemNameException
     * @throws TooLongItemNameException
     * @throws TooHighPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     * @throws NegativeItemQuantityException
     */
    public function testEmptyItemNameException(): void
    {
        $this->expectException(EmptyItemNameException::class);
        new Item("  \n\r\t\0  ", 2, 3);
    }

    /**
     * Тестирует выброс исключения при установке слишком высокой цены предмета расчёта
     *
     * @covers \AtolOnline\Entities\Item
     * @covers \AtolOnline\Entities\Item::setPrice
     * @covers \AtolOnline\Exceptions\TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooHighPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     * @throws NegativeItemQuantityException
     */
    public function testTooHighPriceException(): void
    {
        $this->expectException(TooHighPriceException::class);
        new Item('test', Constraints::MAX_COUNT_ITEM_PRICE + 0.1, 3);
    }

    /**
     * Тестирует выброс исключения при получении слишком высокой стоимости предмета расчёта
     *
     * @covers \AtolOnline\Entities\Item
     * @covers \AtolOnline\Entities\Item::setPrice
     * @covers \AtolOnline\Exceptions\TooHighSumException
     * @throws TooHighSumException
     */
    public function testTooHighSumException(): void
    {
        $this->expectException(TooHighSumException::class);
        (new Item('test', Constraints::MAX_COUNT_ITEM_PRICE, Constraints::MAX_COUNT_ITEM_QUANTITY))->getSum();
    }

    /**
     * Тестирует выброс исключения при установке слишком высокой цены предмета расчёта
     *
     * @covers \AtolOnline\Entities\Item
     * @covers \AtolOnline\Entities\Item::setPrice
     * @covers \AtolOnline\Exceptions\NegativeItemPriceException
     * @throws TooLongItemNameException
     * @throws TooHighPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     * @throws NegativeItemQuantityException
     */
    public function testNegativeItemPriceException(): void
    {
        $this->expectException(NegativeItemPriceException::class);
        new Item('test', -0.01, 3);
    }

    /**
     * Тестирует выброс исключения при установке слишком большого количества предмета расчёта
     *
     * @covers \AtolOnline\Entities\Item
     * @covers \AtolOnline\Entities\Item::setQuantity
     * @covers \AtolOnline\Exceptions\TooHighItemQuantityException
     * @throws TooLongItemNameException
     * @throws TooHighPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     * @throws NegativeItemQuantityException
     */
    public function testTooHighItemQuantityException(): void
    {
        $this->expectException(TooHighItemQuantityException::class);
        new Item('test', 2, Constraints::MAX_COUNT_ITEM_QUANTITY + 1);
    }

    /**
     * Тестирует выброс исключения при установке отрицательного количества предмета расчёта
     *
     * @covers \AtolOnline\Entities\Item
     * @covers \AtolOnline\Entities\Item::setQuantity
     * @covers \AtolOnline\Exceptions\NegativeItemQuantityException
     * @throws TooLongItemNameException
     * @throws TooHighPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     */
    public function testNegativeItemQuantityException(): void
    {
        $this->expectException(NegativeItemQuantityException::class);
        new Item('test', 2, -0.01);
    }

    /**
     * Тестирует обнуление единицы измерения
     *
     * @param mixed $param
     * @dataProvider providerNullableStrings
     * @covers       \AtolOnline\Entities\Item::setMeasurementUnit
     * @covers       \AtolOnline\Entities\Item::getMeasurementUnit
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooLongMeasurementUnitException
     * @throws TooManyException
     * @throws NegativeItemQuantityException
     */
    public function testNullableMeasurementUnit(mixed $param): void
    {
        $this->assertNull((new Item('test item', 2, 3))->setMeasurementUnit($param)->getMeasurementUnit());
    }

    /**
     * Тестирует выброс исключения при установке слишком длинной единицы измерения
     *
     * @covers \AtolOnline\Entities\Item::setMeasurementUnit
     * @covers \AtolOnline\Exceptions\TooLongMeasurementUnitException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooLongMeasurementUnitException
     * @throws TooManyException
     */
    public function testTooLongMeasurementUnitException(): void
    {
        $this->expectException(TooLongMeasurementUnitException::class);
        (new Item('test item', 2, 3))
            ->setMeasurementUnit(Helpers::randomStr(Constraints::MAX_LENGTH_MEASUREMENT_UNIT + 1));
    }

    /**
     * Тестирует сеттеры-геттеры валидных перечислимых значений атрибутов
     *
     * @covers \AtolOnline\Entities\Item::setPaymentMethod
     * @covers \AtolOnline\Entities\Item::getPaymentMethod
     * @covers \AtolOnline\Entities\Item::setPaymentObject
     * @covers \AtolOnline\Entities\Item::getPaymentObject
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws EmptyItemNameException
     * @throws InvalidEnumValueException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws TooHighPriceException
     * @throws NegativeItemQuantityException
     * @throws TooLongItemNameException
     */
    public function testValidEnums(): void
    {
        $item = new Item('test item', 2, 3);
        $this->assertEquals(
            PaymentMethods::ADVANCE,
            $item->setPaymentMethod(PaymentMethods::ADVANCE)->getPaymentMethod()
        );
        $this->assertEquals(
            PaymentObjects::COMMODITY,
            $item->setPaymentObject(PaymentObjects::COMMODITY)->getPaymentObject()
        );
        $this->assertAtolable($item, [
            'name' => 'test item',
            'price' => 2,
            'quantity' => 3,
            'sum' => 6,
            'payment_method' => 'advance',
            'payment_object' => 'commodity',
        ]);
    }

    /**
     * Тестирует установку невалидного способа оплаты
     *
     * @covers \AtolOnline\Entities\Item::setPaymentMethod
     * @covers \AtolOnline\Exceptions\InvalidEnumValueException
     * @throws EmptyItemNameException
     * @throws InvalidEnumValueException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws TooHighPriceException
     * @throws NegativeItemQuantityException
     * @throws TooLongItemNameException
     */
    public function testInvalidPaymentMethod(): void
    {
        $this->expectException(InvalidEnumValueException::class);
        $this->expectExceptionMessage('Некорректное значение AtolOnline\Enums\PaymentMethods::wrong_value');
        (new Item('test item', 2, 3))->setPaymentMethod('wrong_value');
    }

    /**
     * Тестирует установку невалидного предмета расчёта
     *
     * @covers \AtolOnline\Entities\Item::setPaymentObject
     * @covers \AtolOnline\Exceptions\InvalidEnumValueException
     * @throws EmptyItemNameException
     * @throws InvalidEnumValueException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws TooHighPriceException
     * @throws NegativeItemQuantityException
     * @throws TooLongItemNameException
     */
    public function testInvalidPaymentObject(): void
    {
        $this->expectException(InvalidEnumValueException::class);
        $this->expectExceptionMessage('Некорректное значение AtolOnline\Enums\PaymentObjects::wrong_value');
        (new Item('test item', 2, 3))->setPaymentObject('wrong_value');
    }

    /**
     * Тестирует установку ставки НДС по строковой константе типа ставки
     *
     * @covers \AtolOnline\Entities\Item::setVat
     * @covers \AtolOnline\Entities\Item::getVat
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     */
    public function testValidVatByString(): void
    {
        $item = (new Item('test item', 2, 3))->setVat(VatTypes::VAT20);
        $this->assertIsSameClass(Vat::class, $item->getVat());
        $this->assertEquals(VatTypes::VAT20, $item->getVat()->getType());
        $this->assertEquals($item->getSum(), $item->getVat()->getSum());
        $this->assertAtolable($item, [
            'name' => 'test item',
            'price' => 2,
            'quantity' => 3,
            'sum' => 6,
            'vat' => [
                'type' => 'vat20',
                'sum' => 1.2,
            ],
        ]);
    }

    /**
     * Тестирует установку ставки НДС объектом
     *
     * @covers \AtolOnline\Entities\Item::setVat
     * @covers \AtolOnline\Entities\Item::getVat
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     */
    public function testValidVatByObject(): void
    {
        $vat = new Vat(VatTypes::VAT20, 4000);
        $item = (new Item('test item', 2, 3))->setVat($vat);
        $this->assertIsSameClass(Vat::class, $item->getVat());
        $this->assertEquals(VatTypes::VAT20, $item->getVat()->getType());
        $this->assertEquals($item->getSum(), $item->getVat()->getSum());
        $this->assertAtolable($item, [
            'name' => 'test item',
            'price' => 2,
            'quantity' => 3,
            'sum' => 6,
            'vat' => [
                'type' => 'vat20',
                'sum' => 1.2,
            ],
        ]);
    }

    /**
     * Тестирует обнуление ставки НДС
     *
     * @param mixed $vat
     * @dataProvider providerNullableStrings
     * @covers       \AtolOnline\Entities\Item::setVat
     * @covers       \AtolOnline\Entities\Item::getVat
     * @covers       \AtolOnline\Entities\Item::jsonSerialize
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws InvalidEnumValueException
     */
    public function testNullableVatByString(mixed $vat): void
    {
        $item = (new Item('test item', 2, 3))->setVat($vat);
        $this->assertNull($item->getVat());
    }

    /**
     * Тестирует установку атрибутов агента
     *
     * @covers \AtolOnline\Entities\Item::setAgentInfo
     * @covers \AtolOnline\Entities\Item::getAgentInfo
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws EmptyItemNameException
     * @throws InvalidEnumValueException
     * @throws InvalidInnLengthException
     * @throws InvalidPhoneException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooLongPayingAgentOperationException
     * @throws TooManyException
     */
    public function testAgentInfo(): void
    {
        $agent_info = new AgentInfo(
            AgentTypes::ANOTHER,
            new PayingAgent('test', ['+79518888888']),
            new ReceivePaymentsOperator(['+79519999999']),
            new MoneyTransferOperator('MTO Name', '9876543210', 'London', ['+79517777777']),
        );
        $item = (new Item('test item', 2, 3))->setAgentInfo($agent_info);
        $this->assertEquals($agent_info, $item->getAgentInfo());
    }

    /**
     * Тестирует установку поставщика
     *
     * @covers \AtolOnline\Entities\Item::setSupplier
     * @covers \AtolOnline\Entities\Item::getSupplier
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws EmptyItemNameException
     * @throws InvalidInnLengthException
     * @throws InvalidPhoneException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     */
    public function testSupplier(): void
    {
        $supplier = new Supplier(
            'some name',
            '+fasd3\qe3fs_=nac99013928czc',
            ['+122997365456'],
        );
        $item = (new Item('test item', 2, 3))->setSupplier($supplier);
        $this->assertEquals($supplier, $item->getSupplier());
        $this->assertAtolable($item, [
            'name' => 'test item',
            'price' => 2,
            'quantity' => 3,
            'sum' => 6,
            'supplier_info' => [
                'name' => 'some name',
                'inn' => '3399013928',
                'phones' => ['+122997365456'],
            ],
        ]);
    }

    /**
     * Тестирует установку валидных пользовательских данных
     *
     * @covers \AtolOnline\Entities\Item::setUserData
     * @covers \AtolOnline\Entities\Item::getUserData
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooLongUserdataException
     * @throws TooManyException
     */
    public function testValidUserdata(): void
    {
        $this->assertAtolable(
            (new Item('test item', 2, 3))
                ->setUserData($user_data = Helpers::randomStr(Constraints::MAX_LENGTH_USER_DATA)),
            [
                'name' => 'test item',
                'price' => 2,
                'quantity' => 3,
                'sum' => 6,
                'user_data' => $user_data,
            ]
        );
    }

    /**
     * Тестирует обнуление пользовательских данных
     *
     * @param mixed $param
     * @dataProvider providerNullableStrings
     * @covers       \AtolOnline\Entities\Item::setUserData
     * @covers       \AtolOnline\Entities\Item::getUserData
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws NegativeItemQuantityException
     * @throws TooLongUserdataException
     */
    public function testNullableUserData(mixed $param): void
    {
        $item = new Item('test item', 2, 3);
        $this->assertNull($item->setUserData($param)->getUserData());
    }

    /**
     * Тестирует выброс исключения при установке слишком длинных польз. данных
     *
     * @covers \AtolOnline\Entities\Item::setUserData
     * @covers \AtolOnline\Exceptions\TooLongUserdataException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooLongUserdataException
     * @throws TooManyException
     */
    public function testTooLongUserdataException(): void
    {
        $this->expectException(TooLongUserdataException::class);
        (new Item('test item', 2, 3))->setUserData(Helpers::randomStr(Constraints::MAX_LENGTH_USER_DATA + 1));
    }

    /**
     * Тестирует установку кода страны происхождения товара
     *
     * @covers \AtolOnline\Entities\Item::setCountryCode
     * @covers \AtolOnline\Entities\Item::getCountryCode
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws EmptyItemNameException
     * @throws InvalidOKSMCodeException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     */
    public function testCountryCode(): void
    {
        $this->assertAtolable(
            (new Item('test item', 2, 3))->setCountryCode('800'),
            [
                'name' => 'test item',
                'price' => 2,
                'quantity' => 3,
                'sum' => 6,
                'country_code' => '800',
            ]
        );
    }

    /**
     * Тестирует выброс исключения при установке невалидного кода страны происхождения товара
     *
     * @covers \AtolOnline\Entities\Item::setCountryCode
     * @covers \AtolOnline\Exceptions\InvalidOKSMCodeException
     * @throws EmptyItemNameException
     * @throws InvalidOKSMCodeException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     */
    public function testInvalidOKSMCodeException(): void
    {
        $this->expectException(InvalidOKSMCodeException::class);
        (new Item('test item', 2, 3))->setCountryCode(Helpers::randomStr());
    }

    /**
     * Тестирует установку валидного кода таможенной декларации
     *
     * @covers \AtolOnline\Entities\Item::getDeclarationNumber
     * @covers \AtolOnline\Entities\Item::setDeclarationNumber
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws InvalidDeclarationNumberException
     */
    public function testValidDeclarationNumber(): void
    {
        $this->assertAtolable(
            (new Item('test item', 2, 3))
                ->setDeclarationNumber($code = Helpers::randomStr()),
            [
                'name' => 'test item',
                'price' => 2,
                'quantity' => 3,
                'sum' => 6,
                'declaration_number' => $code,
            ]
        );
    }

    /**
     * Тестирует выброс исключения при установке слишком короткого кода таможенной декларации
     *
     * @covers \AtolOnline\Entities\Item::setDeclarationNumber
     * @covers \AtolOnline\Exceptions\InvalidDeclarationNumberException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws InvalidDeclarationNumberException
     * @throws TooManyException
     */
    public function testInvalidDeclarationNumberExceptionMin(): void
    {
        $this->expectException(InvalidDeclarationNumberException::class);
        (new Item('test item', 2, 3))
            ->setDeclarationNumber(Helpers::randomStr(Constraints::MIN_LENGTH_DECLARATION_NUMBER - 1));
    }

    /**
     * Тестирует выброс исключения при установке слишком длинного кода таможенной декларации
     *
     * @covers \AtolOnline\Entities\Item::setDeclarationNumber
     * @covers \AtolOnline\Exceptions\InvalidDeclarationNumberException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws InvalidDeclarationNumberException
     * @throws TooManyException
     */
    public function testInvalidDeclarationNumberExceptionMax(): void
    {
        $this->expectException(InvalidDeclarationNumberException::class);
        (new Item('test item', 2, 3))
            ->setDeclarationNumber(Helpers::randomStr(Constraints::MAX_LENGTH_DECLARATION_NUMBER + 1));
    }

    /**
     * Тестирует установку акциза и расчёт суммы с его учётом
     *
     * @covers \AtolOnline\Entities\Item::setExcise
     * @covers \AtolOnline\Entities\Item::getExcise
     * @covers \AtolOnline\Entities\Item::getSum
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws TooLongItemNameException
     * @throws TooHighPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     * @throws NegativeItemQuantityException
     * @throws NegativeItemExciseException
     */
    public function testExcise(): void
    {
        $this->assertAtolable(
            (new Item('test item', 2, 3))->setExcise(1),
            [
                'name' => 'test item',
                'price' => 2,
                'quantity' => 3,
                'sum' => 7,
                'excise' => 1,
            ]
        );
    }

    /**
     * Тестирует выброс исключения при установке слишком отрицательного акциза
     *
     * @covers \AtolOnline\Entities\Item::setExcise
     * @covers \AtolOnline\Exceptions\NegativeItemExciseException
     * @throws TooLongItemNameException
     * @throws TooHighPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     * @throws NegativeItemQuantityException
     * @throws NegativeItemExciseException
     */
    public function testNegativeItemExciseException(): void
    {
        $this->expectException(NegativeItemExciseException::class);
        (new Item('test item', 2, 3))->setExcise(-1);
    }

    /**
     * Тестирует установку валидного кода товара
     *
     * @covers \AtolOnline\Entities\Item::setCode
     * @covers \AtolOnline\Entities\Item::getCode
     * @covers \AtolOnline\Entities\Item::getCodeHex
     * @covers \AtolOnline\Entities\Item::jsonSerialize
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws TooLongItemCodeException
     */
    public function testValidNomenclatureCode(): void
    {
        $code = Helpers::randomStr(Constraints::MAX_LENGTH_ITEM_CODE);
        $encoded = trim(preg_replace('/([\dA-Fa-f]{2})/', '$1 ', bin2hex($code)));

        $item = (new Item('test item', 2, 3))->setCode($code);
        $this->assertEquals($code, $item->getCode());
        $this->assertEquals($encoded, $item->getCodeHex());

        $decoded = hex2bin(str_replace(' ', '', $item->getCodeHex()));
        $this->assertEquals($decoded, $item->getCode());

        $this->assertAtolable($item, [
            'name' => 'test item',
            'price' => 2,
            'quantity' => 3,
            'sum' => 6,
            'nomenclature_code' => $item->getCodeHex(),
        ]);
    }

    /**
     * Тестирует обнуление кода товара
     *
     * @param mixed $param
     * @dataProvider providerNullableStrings
     * @covers       \AtolOnline\Entities\Item::setCode
     * @covers       \AtolOnline\Entities\Item::getCode
     * @covers       \AtolOnline\Entities\Item::getCodeHex
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighPriceException
     * @throws TooLongItemCodeException
     * @throws TooLongItemNameException
     * @throws TooManyException
     */
    public function testNullableCode(mixed $param): void
    {
        $item = (new Item('test item', 2, 3))->setCode($param);
        $this->assertNull($item->getCode());
        $this->assertNull($item->getCodeHex());
    }

    /**
     * Тестирует выброс исключения при установке слишком отрицательного акциза
     *
     * @covers \AtolOnline\Entities\Item::setCode
     * @covers \AtolOnline\Exceptions\TooLongItemCodeException
     * @throws TooLongItemNameException
     * @throws TooHighPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     * @throws NegativeItemQuantityException
     */
    public function testTooLongItemCodeException(): void
    {
        $this->expectException(TooLongItemCodeException::class);
        (new Item('test item', 2, 3))->setCode(Helpers::randomStr(Constraints::MAX_LENGTH_ITEM_CODE + 1));
    }
}
