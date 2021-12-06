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
    Entities\Vat,
    Entities\Vats,
    Enums\VatTypes,
    Exceptions\InvalidEnumValueException,
    Exceptions\TooManyVatsException,
    Tests\BasicTestCase};
use Exception;

/**
 * Набор тестов для проверки работы класса коллекции объектов на примере класса коллекции ставок НДС
 */
class VatsTest extends BasicTestCase
{
    /**
     * Тестирует создание коллекции ставок
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @throws InvalidEnumValueException
     */
    public function testConstructor()
    {
        $vats = new Vats($this->generateObjects(3));
        $this->assertIsCollection($vats);
        $this->assertEquals(3, $vats->count());
    }

    /**
     * Тестирует выброс исключения при установке слишком большого количества ставок через конструктор
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     */
    public function testTooManyVatsExceptionByConstructor()
    {
        $this->expectException(TooManyVatsException::class);
        new Vats($this->generateObjects(Constraints::MAX_COUNT_DOC_VATS + 1));
    }

    /**
     * Тестирует добавление ставки в начало коллекции
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::prepend
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @throws InvalidEnumValueException
     */
    public function testPrepend()
    {
        $vats = (new Vats($this->generateObjects(3)))
            ->prepend($this->generateObjects());
        $this->assertEquals(4, $vats->count());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в начало коллекции
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::prepend
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     */
    public function testTooManyVatsExceptionByPrepend()
    {
        $this->expectException(TooManyVatsException::class);
        (new Vats($this->generateObjects(Constraints::MAX_COUNT_DOC_VATS + 1)))
            ->prepend($this->generateObjects());
    }

    /**
     * Тестирует добавление ставки в конец коллекции
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::add
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @throws InvalidEnumValueException
     */
    public function testAdd()
    {
        $vats = (new Vats($this->generateObjects(3)))
            ->add($this->generateObjects());
        $this->assertEquals(4, $vats->count());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в конец коллекции
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::add
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     */
    public function testTooManyVatsExceptionByAdd()
    {
        $this->expectException(TooManyVatsException::class);
        (new Vats($this->generateObjects(Constraints::MAX_COUNT_DOC_VATS + 1)))
            ->add($this->generateObjects());
    }

    /**
     * Тестирует добавление лишних ставок в конец коллекции
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::push
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @throws InvalidEnumValueException
     */
    public function testPush()
    {
        $vats = (new Vats($this->generateObjects(3)))
            ->push(...$this->generateObjects(3));
        $this->assertEquals(6, $vats->count());
    }

    /**
     * Тестирует выброс исключения при добавлении лишних ставок в конец коллекции
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::push
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     */
    public function testTooManyVatsExceptionByPush()
    {
        $this->expectException(TooManyVatsException::class);
        (new Vats($this->generateObjects(Constraints::MAX_COUNT_DOC_VATS + 1)))
            ->push(...$this->generateObjects());
    }

    /**
     * Тестирует добавление ставки в начало коллекции
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::merge
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @throws InvalidEnumValueException
     */
    public function testMerge()
    {
        $vats = (new Vats($this->generateObjects(3)))
            ->merge($this->generateObjects(3));
        $this->assertEquals(6, $vats->count());
    }

    /**
     * Тестирует выброс исключения при добавлении лишней ставки в начало коллекции
     *
     * @covers \AtolOnline\Entities\EntityCollection
     * @covers \AtolOnline\Entities\EntityCollection::merge
     * @covers \AtolOnline\Entities\EntityCollection::checkCount
     * @covers \AtolOnline\Exceptions\TooManyVatsException
     * @throws InvalidEnumValueException
     */
    public function testTooManyVatsExceptionByMerge()
    {
        $this->expectException(TooManyVatsException::class);
        (new Vats($this->generateObjects(9)))
            ->merge($this->generateObjects(2));
    }

    /**
     * Генерирует массив тестовых объектов ставок НДС
     *
     * @throws InvalidEnumValueException
     * @throws Exception
     */
    protected function generateObjects(int $count = 1): array
    {
        $types = VatTypes::toArray();
        $result = [];
        for ($i = 0; $i < abs($count); ++$i) {
            $result[] = new Vat(
                array_values($types)[random_int(0, count($types) - 1)],
                random_int(1, 100) * 2 / 3
            );
        }
        return $result;
    }
}
