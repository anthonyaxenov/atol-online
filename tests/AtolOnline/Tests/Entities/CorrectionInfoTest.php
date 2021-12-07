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
    Entities\CorrectionInfo,
    Enums\CorrectionTypes,
    Exceptions\EmptyCorrectionNumberException,
    Exceptions\InvalidCorrectionDateException,
    Exceptions\InvalidEnumValueException,
    Helpers,
    Tests\BasicTestCase};
use Exception;

/**
 * Набор тестов для проверки работы класса данных коррекции
 */
class CorrectionInfoTest extends BasicTestCase
{
    /**
     * Тестирует конструктор
     *
     * @covers \AtolOnline\Entities\CorrectionInfo
     * @covers \AtolOnline\Entities\CorrectionInfo::setType
     * @covers \AtolOnline\Entities\CorrectionInfo::getType
     * @covers \AtolOnline\Entities\CorrectionInfo::setDate
     * @covers \AtolOnline\Entities\CorrectionInfo::getDate
     * @covers \AtolOnline\Entities\CorrectionInfo::setNumber
     * @covers \AtolOnline\Entities\CorrectionInfo::getNumber
     * @covers \AtolOnline\Entities\CorrectionInfo::jsonSerialize
     * @return void
     * @throws InvalidEnumValueException
     * @throws InvalidCorrectionDateException
     * @throws EmptyCorrectionNumberException
     * @throws Exception
     */
    public function testConstructor(): void
    {
        $this->assertIsAtolable(
            new CorrectionInfo(CorrectionTypes::SELF, '01.01.2021', $number = Helpers::randomStr()),
            [
                'type' => CorrectionTypes::SELF,
                'base_date' => '01.01.2021',
                'base_number' => $number,
            ]
        );
    }

    /**
     * Тестирует исключение при некорректном типе
     *
     * @covers \AtolOnline\Entities\CorrectionInfo
     * @covers \AtolOnline\Entities\CorrectionInfo::setType
     * @covers \AtolOnline\Enums\CorrectionTypes::isValid
     * @covers \AtolOnline\Exceptions\InvalidEnumValueException
     * @return void
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     * @throws InvalidEnumValueException
     */
    public function testInvalidEnumValueException(): void
    {
        $this->expectException(InvalidEnumValueException::class);
        $this->expectExceptionMessage('Некорректное значение AtolOnline\Enums\CorrectionTypes::wrong_value');
        new CorrectionInfo('wrong_value', '01.01.2021', Helpers::randomStr());
    }

    /**
     * Тестирует исключение при некорректной дате
     *
     * @covers \AtolOnline\Entities\CorrectionInfo
     * @covers \AtolOnline\Entities\CorrectionInfo::setDate
     * @covers \AtolOnline\Enums\CorrectionTypes::isValid
     * @covers \AtolOnline\Exceptions\InvalidCorrectionDateException
     * @return void
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     * @throws InvalidEnumValueException
     */
    public function testInvalidCorrectionDateException(): void
    {
        $this->expectException(InvalidCorrectionDateException::class);
        new CorrectionInfo(CorrectionTypes::SELF, Helpers::randomStr(), Helpers::randomStr());
    }

    /**
     * Тестирует исключение при некорректной дате
     *
     * @covers \AtolOnline\Entities\CorrectionInfo
     * @covers \AtolOnline\Entities\CorrectionInfo::setNumber
     * @covers \AtolOnline\Enums\CorrectionTypes::isValid
     * @covers \AtolOnline\Exceptions\EmptyCorrectionNumberException
     * @return void
     */
    public function testEmptyCorrectionNumberException(): void
    {
        $this->expectException(EmptyCorrectionNumberException::class);
        new CorrectionInfo(CorrectionTypes::SELF, '01.01.2021', "\n\r\t\0");
    }
}
