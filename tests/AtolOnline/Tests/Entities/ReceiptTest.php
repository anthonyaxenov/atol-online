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
    Collections\Items,
    Collections\Payments,
    Collections\Vats,
    Entities\AgentInfo,
    Entities\Client,
    Entities\Company,
    Entities\Item,
    Entities\MoneyTransferOperator,
    Entities\PayingAgent,
    Entities\Receipt,
    Entities\ReceivePaymentsOperator,
    Entities\Supplier,
    Entities\Vat,
    Enums\AgentTypes,
    Enums\SnoTypes,
    Tests\BasicTestCase};
use AtolOnline\Exceptions\{
    EmptyItemNameException,
    EmptyItemsException,
    EmptyPaymentsException,
    EmptyVatsException,
    InvalidEntityInCollectionException,
    InvalidEnumValueException,
    InvalidInnLengthException,
    InvalidPhoneException,
    NegativeItemPriceException,
    NegativeItemQuantityException,
    NegativePaymentSumException,
    TooHighItemPriceException,
    TooHighItemSumException,
    TooHighPaymentSumException,
    TooLongItemNameException,
    TooLongPayingAgentOperationException,
    TooManyException};
use Exception;

/**
 * Набор тестов для проверки работы класса чека прихода, расхода, возврата прихода, возврата расхода
 */
class ReceiptTest extends BasicTestCase
{
    /**
     * Тестирует конструктор и корректное приведение к json
     *
     * @covers \AtolOnline\Entities\Receipt
     * @covers \AtolOnline\Entities\Receipt::setClient
     * @covers \AtolOnline\Entities\Receipt::setCompany
     * @covers \AtolOnline\Entities\Receipt::setItems
     * @covers \AtolOnline\Entities\Receipt::setPayments
     * @covers \AtolOnline\Entities\Receipt::getClient
     * @covers \AtolOnline\Entities\Receipt::getCompany
     * @covers \AtolOnline\Entities\Receipt::getItems
     * @covers \AtolOnline\Entities\Receipt::getPayments
     * @covers \AtolOnline\Entities\Receipt::getTotal
     * @covers \AtolOnline\Entities\Receipt::jsonSerialize
     * @throws TooHighItemPriceException
     * @throws NegativePaymentSumException
     * @throws NegativeItemPriceException
     * @throws EmptyPaymentsException
     * @throws TooHighPaymentSumException
     * @throws EmptyItemsException
     * @throws EmptyItemNameException
     * @throws TooManyException
     * @throws InvalidEnumValueException
     * @throws InvalidEntityInCollectionException
     * @throws TooLongItemNameException
     * @throws NegativeItemQuantityException
     * @throws Exception
     */
    public function testConstructor(): void
    {
        $receipt = $this->newReceipt();
        $this->assertIsAtolable($receipt);
        $receipt->getItems();
    }

    /**
     * Тестирует установку данных агента
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt::setAgentInfo
     * @covers \AtolOnline\Entities\Receipt::getAgentInfo
     * @covers \AtolOnline\Entities\Receipt::jsonSerialize
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyPaymentsException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws InvalidInnLengthException
     * @throws InvalidPhoneException
     * @throws TooLongPayingAgentOperationException
     * @throws Exception
     */
    public function testAgentInfo(): void
    {
        $agent_info = new AgentInfo(
            AgentTypes::ANOTHER,
            new PayingAgent('test', ['+79518888888']),
            new ReceivePaymentsOperator(['+79519999999']),
            new MoneyTransferOperator('MTO Name', '9876543210', 'London', ['+79517777777']),
        );
        $receipt = $this->newReceipt()->setAgentInfo($agent_info);
        $this->assertArrayHasKey('agent_info', $receipt->jsonSerialize());
        $this->assertEquals($receipt->getAgentInfo(), $receipt->jsonSerialize()['agent_info']);
        $this->assertArrayNotHasKey('agent_info', $receipt->setAgentInfo(null)->jsonSerialize());
    }

    /**
     * Тестирует установку данных поставщика
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt::setSupplier
     * @covers \AtolOnline\Entities\Receipt::getSupplier
     * @covers \AtolOnline\Entities\Receipt::jsonSerialize
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyPaymentsException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws InvalidInnLengthException
     * @throws InvalidPhoneException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws Exception
     */
    public function testSupplier(): void
    {
        $supplier = new Supplier('some name', '+fasd3\qe3fs_=nac99013928czc', ['+122997365456']);
        $receipt = $this->newReceipt()->setSupplier($supplier);
        $this->assertArrayHasKey('supplier_info', $receipt->jsonSerialize());
        $this->assertEquals($receipt->getSupplier(), $receipt->jsonSerialize()['supplier_info']);
        $this->assertArrayNotHasKey('supplier_info', $receipt->setSupplier(null)->jsonSerialize());
    }

    /**
     * Тестирует выброс исключения при передаче пустой коллекции предметов расчёта
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt
     * @covers \AtolOnline\Entities\Receipt::setItems
     * @covers \AtolOnline\Collections\Items::checkCount
     * @covers \AtolOnline\Exceptions\EmptyItemsException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws InvalidEntityInCollectionException
     * @throws EmptyPaymentsException
     */
    public function testEmptyItemsException(): void
    {
        $this->expectException(EmptyItemsException::class);
        new Receipt(
            new Client('John Doe', 'john@example.com', '+1/22/99*73s dsdas654 5s6', '+fasd3\qe3fs_=nac99013928czc'),
            new Company('company@example.com', SnoTypes::OSN, '1234567890', 'https://example.com'),
            new Items([]),
            new Payments($this->generatePaymentObjects())
        );
    }

    /**
     * Тестирует выброс исключения при передаче коллекции предметов расчёта с некорректным содержимым
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt
     * @covers \AtolOnline\Entities\Receipt::setItems
     * @covers \AtolOnline\Collections\Items::checkItemsClasses
     * @covers \AtolOnline\Collections\Items::checkItemClass
     * @covers \AtolOnline\Exceptions\InvalidEntityInCollectionException
     * @throws EmptyItemsException
     * @throws EmptyPaymentsException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function testInvalidItemInCollectionException(): void
    {
        $this->expectException(InvalidEntityInCollectionException::class);
        $this->expectErrorMessage('Коллекция AtolOnline\Collections\Items должна содержать объекты AtolOnline\Entities\Item');
        new Receipt(
            new Client('John Doe', 'john@example.com', '+1/22/99*73s dsdas654 5s6', '+fasd3\qe3fs_=nac99013928czc'),
            new Company('company@example.com', SnoTypes::OSN, '1234567890', 'https://example.com'),
            new Items(['qwerty']),
            new Payments($this->generatePaymentObjects())
        );
    }

    /**
     * Тестирует выброс исключения при передаче пустой коллекции оплат
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt
     * @covers \AtolOnline\Entities\Receipt::setPayments
     * @covers \AtolOnline\Collections\Payments::checkCount
     * @covers \AtolOnline\Exceptions\EmptyPaymentsException
     * @throws TooHighPaymentSumException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws InvalidEntityInCollectionException
     * @throws EmptyItemsException
     */
    public function testEmptyPaymentsException(): void
    {
        $this->expectException(EmptyPaymentsException::class);
        new Receipt(
            new Client('John Doe', 'john@example.com', '+1/22/99*73s dsdas654 5s6', '+fasd3\qe3fs_=nac99013928czc'),
            new Company('company@example.com', SnoTypes::OSN, '1234567890', 'https://example.com'),
            new Items([new Item('test item', 2, 3)]),
            new Payments([])
        );
    }

    /**
     * Тестирует выброс исключения при передаче коллекции предметов расчёта с некорректным содержимым
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt
     * @covers \AtolOnline\Entities\Receipt::setPayments
     * @covers \AtolOnline\Collections\Items::checkItemsClasses
     * @covers \AtolOnline\Collections\Items::checkItemClass
     * @covers \AtolOnline\Exceptions\InvalidEntityInCollectionException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyPaymentsException
     * @throws InvalidEntityInCollectionException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     */
    public function testInvalidPaymentInCollectionException(): void
    {
        $this->expectException(InvalidEntityInCollectionException::class);
        $this->expectErrorMessage('Коллекция AtolOnline\Collections\Payments должна содержать объекты AtolOnline\Entities\Payment');
        new Receipt(
            new Client('John Doe', 'john@example.com', '+1/22/99*73s dsdas654 5s6', '+fasd3\qe3fs_=nac99013928czc'),
            new Company('company@example.com', SnoTypes::OSN, '1234567890', 'https://example.com'),
            new Items([new Item('test item', 2, 3)]),
            new Payments(['qwerty'])
        );
    }

    /**
     * Тестирует выброс исключения при передаче пустой коллекции ставок НДС
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt
     * @covers \AtolOnline\Entities\Receipt::setVats
     * @covers \AtolOnline\Collections\Vats::checkCount
     * @covers \AtolOnline\Exceptions\EmptyVatsException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyPaymentsException
     * @throws EmptyVatsException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooManyException
     */
    public function testEmptyVatsException(): void
    {
        $this->expectException(EmptyVatsException::class);
        $this->newReceipt()->setVats(new Vats([]));
    }

    /**
     * Тестирует выброс исключения при передаче коллекции ставок НДС с некорректным содержимым
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt
     * @covers \AtolOnline\Entities\Receipt::setVats
     * @covers \AtolOnline\Collections\Vats::checkItemsClasses
     * @covers \AtolOnline\Collections\Vats::checkItemClass
     * @covers \AtolOnline\Exceptions\InvalidEntityInCollectionException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyPaymentsException
     * @throws EmptyVatsException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooManyException
     */
    public function testInvalidVatInCollectionException(): void
    {
        $this->expectException(InvalidEntityInCollectionException::class);
        $this->expectErrorMessage('Коллекция AtolOnline\Collections\Vats должна содержать объекты AtolOnline\Entities\Vat');
        $this->newReceipt()->setVats(new Vats(['qwerty']));
    }

    /**
     * Тестирует просчёт общей суммы чека и ставок НДС
     *
     * @covers \AtolOnline\Entities\Receipt::setVats
     * @covers \AtolOnline\Entities\Receipt::getVats
     * @covers \AtolOnline\Entities\Receipt::getTotal
     * @throws TooHighItemPriceException
     * @throws NegativeItemPriceException
     * @throws EmptyPaymentsException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws TooHighItemSumException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws EmptyItemsException
     * @throws EmptyItemNameException
     * @throws TooManyException
     * @throws NegativeItemQuantityException
     * @throws TooLongItemNameException
     * @throws Exception
     */
    public function testCalculations(): void
    {
        $items_total = 0;
        $receipt = $this->newReceipt();

        //TODO при $receipt->getItems()->pluck('sum') стреляет InvalidEntityInCollectionException
        // см. примечания в конструкторе EntityCollection
        $receipt->getItems()->each(function ($item) use (&$items_total) {
            /** @var Item $item */
            return $items_total += $item->getSum();
        });
        $this->assertEquals($items_total, $receipt->getTotal());

        /** @var Vat $vat */
        $receipt->setVats(new Vats($this->generateVatObjects(2)))->getVats()
            ->each(fn ($vat) => $this->assertEquals($items_total, $vat->getSum()));
    }

    /**
     * Возвращает валидный тестовый объект чека
     *
     * @return Receipt
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyPaymentsException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooManyException
     */
    protected function newReceipt(): Receipt
    {
        return new Receipt(
            new Client('John Doe', 'john@example.com', '+1/22/99*73s dsdas654 5s6', '+fasd3\qe3fs_=nac99013928czc'),
            new Company('company@example.com', SnoTypes::OSN, '1234567890', 'https://example.com'),
            new Items($this->generateItemObjects(2)),
            new Payments($this->generatePaymentObjects())
        );
    }
}
