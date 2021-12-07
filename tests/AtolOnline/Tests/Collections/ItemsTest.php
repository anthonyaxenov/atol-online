<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Tests\Collections;

use AtolOnline\{
    Collections\Items,
    Constants\Constraints,
    Tests\BasicTestCase};
use AtolOnline\Exceptions\{
    EmptyItemNameException,
    InvalidEntityInCollectionException,
    NegativeItemPriceException,
    NegativeItemQuantityException,
    TooHighItemPriceException,
    TooLongItemNameException,
    TooManyException,
    TooManyItemsException};

/**
 * Набор тестов для проверки работы класса коллекции предметов расчёта
 */
class ItemsTest extends BasicTestCase
{
    /**
     * Тестирует выброс исключения при установке слишком большого количества оплат через конструктор
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyItemsException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyItemsExceptionByConstructor()
    {
        $this->expectException(TooManyItemsException::class);
        new Items($this->generateItemObjects(Constraints::MAX_COUNT_DOC_ITEMS + 1));
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в начало коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection::prepend
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyItemsException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyItemsExceptionByPrepend()
    {
        $this->expectException(TooManyItemsException::class);
        (new Items($this->generateItemObjects(Constraints::MAX_COUNT_DOC_ITEMS)))
            ->prepend($this->generateItemObjects());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в конец коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::add
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyItemsException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyItemsExceptionByAdd()
    {
        $this->expectException(TooManyItemsException::class);
        (new Items($this->generateItemObjects(Constraints::MAX_COUNT_DOC_ITEMS)))
            ->add($this->generateItemObjects());
    }

    /**
     * Тестирует выброс исключения при добавлении лишних оплат в конец коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::push
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyItemsException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyItemsExceptionByPush()
    {
        $this->expectException(TooManyItemsException::class);
        (new Items($this->generateItemObjects(Constraints::MAX_COUNT_DOC_ITEMS)))
            ->push(...$this->generateItemObjects());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в начало коллекции
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::merge
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyItemsException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyItemsExceptionByMerge()
    {
        $this->expectException(TooManyItemsException::class);
        (new Items($this->generateItemObjects(Constraints::MAX_COUNT_DOC_ITEMS)))
            ->merge($this->generateItemObjects(2));
    }
}
