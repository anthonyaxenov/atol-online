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
    Entities\Item,
    Helpers,
    Tests\BasicTestCase};
use AtolOnline\Exceptions\{
    EmptyItemNameException,
    NegativeItemPriceException,
    NegativeItemQuantityException,
    TooHighItemPriceException,
    TooLongItemNameException,
    TooManyException,
    TooManyItemsException,};
use Exception;

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
     */
    public function testTooManyItemsExceptionByConstructor()
    {
        $this->expectException(TooManyItemsException::class);
        new Items($this->generateObjects(Constraints::MAX_COUNT_DOC_ITEMS + 1));
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
     */
    public function testTooManyItemsExceptionByPrepend()
    {
        $this->expectException(TooManyItemsException::class);
        (new Items($this->generateObjects(Constraints::MAX_COUNT_DOC_ITEMS)))
            ->prepend($this->generateObjects());
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
     */
    public function testTooManyItemsExceptionByAdd()
    {
        $this->expectException(TooManyItemsException::class);
        (new Items($this->generateObjects(Constraints::MAX_COUNT_DOC_ITEMS)))
            ->add($this->generateObjects());
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
     */
    public function testTooManyItemsExceptionByPush()
    {
        $this->expectException(TooManyItemsException::class);
        (new Items($this->generateObjects(Constraints::MAX_COUNT_DOC_ITEMS)))
            ->push(...$this->generateObjects());
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
     */
    public function testTooManyItemsExceptionByMerge()
    {
        $this->expectException(TooManyItemsException::class);
        (new Items($this->generateObjects(Constraints::MAX_COUNT_DOC_ITEMS)))
            ->merge($this->generateObjects(2));
    }

    /**
     * Генерирует массив тестовых объектов предметов расчёта
     *
     * @param int $count
     * @return Item[]
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws Exception
     */
    protected function generateObjects(int $count = 1): array
    {
        $result = [];
        for ($i = 0; $i < abs($count); ++$i) {
            $result[] = new Item(Helpers::randomStr(), random_int(1, 100), random_int(1, 10));
        }
        return $result;
    }
}
