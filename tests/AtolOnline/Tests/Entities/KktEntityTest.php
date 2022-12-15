<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types=1);

namespace AtolOnline\Tests\Entities;

use AtolOnline\Entities\Kkt;
use AtolOnline\Exceptions\EmptyMonitorDataException;
use AtolOnline\Exceptions\NotEnoughMonitorDataException;
use AtolOnline\Tests\BasicTestCase;
use DateTime;
use Exception;

class KktEntityTest extends BasicTestCase
{
    /**
     * @var array Данные для создания объекта ККТ
     */
    private array $sample_data = [
        'serialNumber' => '00107703864827',
        'registrationNumber' => '0000000003027865',
        'deviceNumber' => 'KKT024219',
        'fiscalizationDate' => '2019-07-22T14:03:00+00:00',
        'fiscalStorageExpiration' => '2020-11-02T21:00:00+00:00',
        'signedDocuments' => 213350,
        'fiscalStoragePercentageUse' => 85.34,
        'fiscalStorageINN' => '3026455760',
        'fiscalStorageSerialNumber' => '9999078902004339',
        'fiscalStoragePaymentAddress' => 'test.qa.ru',
        'groupCode' => 'test-qa-ru_14605',
        'timestamp' => '2019-12-05T10:45:30+00:00',
        'isShiftOpened' => true,
        'shiftNumber' => 126,
        'shiftReceipt' => 2278,
        //'unsentDocs' => 123,
        'firstUnsetDocTimestamp' => 'there must be timestamp, but we want to get exception here to get string',
        'networkErrorCode' => 2,
    ];

    /**
     * Тестирует создание объекта ККТ с валидными данными
     *
     * @covers  \AtolOnline\Entities\Kkt::__construct
     * @covers  \AtolOnline\Entities\Kkt::__get
     * @covers  \AtolOnline\Entities\Kkt::jsonSerialize
     * @covers  \AtolOnline\Entities\Kkt::__toString
     * @throws Exception
     */
    public function testConstructor(): void
    {
        $kkt = new Kkt((object)$this->sample_data);
        $this->assertIsSameClass(Kkt::class, $kkt);
        $this->assertIsAtolable($kkt);
    }

    /**
     * Тестирует исключение при попытке создать объект ККТ без данных от монитора
     *
     * @covers  \AtolOnline\Entities\Kkt::__construct
     * @covers  \AtolOnline\Exceptions\EmptyMonitorDataException
     * @throws EmptyMonitorDataException
     * @throws NotEnoughMonitorDataException
     */
    public function testEmptyMonitorDataException(): void
    {
        $this->expectException(EmptyMonitorDataException::class);
        new Kkt((object)[]);
    }

    /**
     * Тестирует исключение при попытке создать объект ККТ без данных от монитора
     *
     * @covers  \AtolOnline\Entities\Kkt::__construct
     * @covers  \AtolOnline\Exceptions\NotEnoughMonitorDataException
     * @throws EmptyMonitorDataException
     * @throws NotEnoughMonitorDataException
     */
    public function testNotEnoughMonitorDataException(): void
    {
        $this->expectException(NotEnoughMonitorDataException::class);
        new Kkt(
            (object)[
                'fiscalizationDate' => '2021-11-20T10:21:00+00:00',
            ]
        );
    }

    /**
     * Тестирует получение атрибутов через магический геттер
     *
     * @covers \AtolOnline\Entities\Kkt::__get
     * @throws EmptyMonitorDataException
     * @throws NotEnoughMonitorDataException
     */
    public function testMagicGetter(): void
    {
        $kkt = new Kkt((object)$this->sample_data);

        // string
        $this->assertNotNull($kkt->serialNumber);
        $this->assertIsString($kkt->serialNumber);
        $this->assertSame($this->sample_data['serialNumber'], $kkt->serialNumber);

        // int
        $this->assertNotNull($kkt->signedDocuments);
        $this->assertIsInt($kkt->signedDocuments);

        // float
        $this->assertNotNull($kkt->signedDocuments);
        $this->assertIsFloat($kkt->fiscalStoragePercentageUse);

        // null
        $this->assertNull($kkt->unsentDocs);

        // DateTime
        $this->assertNotNull($kkt->fiscalizationDate);
        $this->assertIsSameClass(DateTime::class, $kkt->fiscalizationDate);
    }

    /**
     * Тестирует исключение при попытке получить некорректный DateTime через магический геттер
     *
     * @covers \AtolOnline\Entities\Kkt::__get
     * @throws EmptyMonitorDataException
     * @throws NotEnoughMonitorDataException
     */
    public function testDateTimeException(): void
    {
        $this->expectException(Exception::class);
        (new Kkt((object)$this->sample_data))->firstUnsetDocTimestamp;
    }
}
