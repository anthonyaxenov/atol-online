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
    Enums\CorrectionType,
    Exceptions\EmptyCorrectionNumberException,
    Exceptions\InvalidCorrectionDateException,
    Helpers,
    Tests\BasicTestCase};
use DateTime;
use DateTimeImmutable;
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
     * @throws InvalidCorrectionDateException
     * @throws EmptyCorrectionNumberException
     * @throws Exception
     */
    public function testConstructor(): void
    {
        $this->assertIsAtolable(
            new CorrectionInfo(CorrectionType::SELF, '01.01.2021', $number = Helpers::randomStr()),
            [
                'type' => CorrectionType::SELF,
                'base_date' => '01.01.2021',
                'base_number' => $number,
            ]
        );

        $this->assertIsAtolable(
            new CorrectionInfo(CorrectionType::SELF, new DateTime('02.02.2022'), $number = Helpers::randomStr()),
            [
                'type' => CorrectionType::SELF,
                'base_date' => '02.02.2022',
                'base_number' => $number,
            ]
        );

        $this->assertIsAtolable(
            new CorrectionInfo(
                CorrectionType::SELF,
                new DateTimeImmutable('03.03.2023'),
                $number = Helpers::randomStr()
            ),
            [
                'type' => CorrectionType::SELF,
                'base_date' => '03.03.2023',
                'base_number' => $number,
            ]
        );
    }

    /**
     * Тестирует исключение при некорректной дате
     *
     * @covers \AtolOnline\Entities\CorrectionInfo
     * @covers \AtolOnline\Entities\CorrectionInfo::setDate
     * @covers \AtolOnline\Exceptions\InvalidCorrectionDateException
     * @return void
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     */
    public function testInvalidCorrectionDateException(): void
    {
        $this->expectException(InvalidCorrectionDateException::class);
        new CorrectionInfo(CorrectionType::SELF, Helpers::randomStr(), Helpers::randomStr());
    }

    /**
     * Тестирует исключение при некорректной дате
     *
     * @covers \AtolOnline\Entities\CorrectionInfo
     * @covers \AtolOnline\Entities\CorrectionInfo::setNumber
     * @covers \AtolOnline\Exceptions\EmptyCorrectionNumberException
     * @return void
     */
    public function testEmptyCorrectionNumberException(): void
    {
        $this->expectException(EmptyCorrectionNumberException::class);
        new CorrectionInfo(CorrectionType::SELF, '01.01.2021', "\n\r\t\0");
    }
}
