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
    Constraints,
    Tests\BasicTestCase};
use AtolOnline\Exceptions\{
    EmptyItemNameException,
    EmptyItemsException,
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
     * Тестирует выброс исключения при установке слишком большого количества предметов расчёта
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Collections\EntityCollection::checkItemsClasses
     * @covers \AtolOnline\Collections\EntityCollection::jsonSerialize
     * @covers \AtolOnline\Exceptions\TooManyItemsException
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws InvalidEntityInCollectionException
     */
    public function testTooManyItemsException()
    {
        $this->expectException(TooManyItemsException::class);
        (new Items($this->generateItemObjects(Constraints::MAX_COUNT_DOC_ITEMS + 1)))->jsonSerialize();
    }

    /**
     * Тестирует выброс исключения при установке нулевого количества предметов расчёта
     *
     * @covers \AtolOnline\Collections\EntityCollection
     * @covers \AtolOnline\Collections\EntityCollection::checkCount
     * @covers \AtolOnline\Collections\EntityCollection::checkItemsClasses
     * @covers \AtolOnline\Collections\EntityCollection::jsonSerialize
     * @covers \AtolOnline\Exceptions\EmptyItemsException
     * @throws InvalidEntityInCollectionException
     */
    public function testEmptyItemsException()
    {
        $this->expectException(EmptyItemsException::class);
        (new Items([]))->jsonSerialize();
    }
}
