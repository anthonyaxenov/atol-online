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
    Entities\AdditionalUserProps,
    Exceptions\EmptyAddUserPropNameException,
    Exceptions\EmptyAddUserPropValueException,
    Exceptions\TooLongAddUserPropNameException,
    Exceptions\TooLongAddUserPropValueException,
    Helpers,
    Tests\BasicTestCase};
use Exception;

/**
 * Набор тестов для проверки работы класса ставки дополнительного реквизита
 */
class AdditionalUserPropsTest extends BasicTestCase
{
    /**
     * Тестирует конструктор
     *
     * @covers \AtolOnline\Entities\AdditionalUserProps
     * @covers \AtolOnline\Entities\AdditionalUserProps::getName
     * @covers \AtolOnline\Entities\AdditionalUserProps::setName
     * @covers \AtolOnline\Entities\AdditionalUserProps::setValue
     * @covers \AtolOnline\Entities\AdditionalUserProps::getValue
     * @covers \AtolOnline\Entities\AdditionalUserProps::jsonSerialize
     * @throws Exception
     */
    public function testConstructor(): void
    {
        $this->assertIsAtolable(
            new AdditionalUserProps('name', 'value'),
            [
                'name' => 'name',
                'value' => 'value',
            ]
        );
    }

    /**
     * Тестирует выброс исключения при слишком длинном наименовании
     *
     * @covers \AtolOnline\Entities\AdditionalUserProps::setName
     * @covers \AtolOnline\Exceptions\TooLongAddUserPropNameException
     * @throws EmptyAddUserPropNameException
     * @throws EmptyAddUserPropValueException
     * @throws TooLongAddUserPropValueException
     */
    public function testTooLongAddCheckPropNameException(): void
    {
        $this->expectException(TooLongAddUserPropNameException::class);
        new AdditionalUserProps(Helpers::randomStr(Constraints::MAX_LENGTH_ADD_USER_PROP_NAME + 1), 'value');
    }

    /**
     * Тестирует выброс исключения при пустом наименовании
     *
     * @covers \AtolOnline\Entities\AdditionalUserProps::setName
     * @covers \AtolOnline\Exceptions\EmptyAddUserPropNameException
     */
    public function testEmptyAddCheckPropNameException(): void
    {
        $this->expectException(EmptyAddUserPropNameException::class);
        new AdditionalUserProps('', 'value');
    }

    /**
     * Тестирует выброс исключения при слишком длинном значении
     *
     * @covers \AtolOnline\Entities\AdditionalUserProps::setValue
     * @covers \AtolOnline\Exceptions\TooLongAddUserPropValueException
     * @throws EmptyAddUserPropNameException
     * @throws EmptyAddUserPropValueException
     * @throws TooLongAddUserPropValueException
     * @throws TooLongAddUserPropNameException
     */
    public function testTooLongAddCheckPropValueException(): void
    {
        $this->expectException(TooLongAddUserPropValueException::class);
        new AdditionalUserProps('name', Helpers::randomStr(Constraints::MAX_LENGTH_ADD_USER_PROP_VALUE + 1));
    }

    /**
     * Тестирует выброс исключения при пустом значении
     *
     * @covers \AtolOnline\Entities\AdditionalUserProps::setValue
     * @covers \AtolOnline\Exceptions\EmptyAddUserPropValueException
     */
    public function testEmptyAddCheckPropValueException(): void
    {
        $this->expectException(EmptyAddUserPropValueException::class);
        new AdditionalUserProps('name', '');
    }
}
