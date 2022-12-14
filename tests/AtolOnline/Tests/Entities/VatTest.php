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
    Entities\Vat,
    Enums\VatType,
    Tests\BasicTestCase};
use Exception;

/**
 * Набор тестов для проверки работы класса ставки НДС
 */
class VatTest extends BasicTestCase
{
    /**
     * Провайдер данных для тестирования разных типов ставок НДС
     *
     * @return array
     */
    public function providerVatsSet(): array
    {
        return [
            [VatType::NONE, 0],
            [VatType::VAT0, 0],
            [VatType::VAT10, 10],
            [VatType::VAT18, 18],
            [VatType::VAT20, 20],
            [VatType::VAT110, 9.09],
            [VatType::VAT118, 15.25],
            [VatType::VAT120, 16.67],
        ];
    }

    /**
     * Провайдер данных для тестирования разных типов ставок НДС
     *
     * @return array
     */
    public function providerVatsAdd(): array
    {
        return [
            [VatType::VAT10, 12, 10],
            [VatType::VAT18, 21.6, 18],
            [VatType::VAT20, 24, 20],
            [VatType::VAT110, 10.91, 9.09],
            [VatType::VAT118, 18.31, 15.25],
            [VatType::VAT120, 20, 16.67],
        ];
    }

    /**
     * Тестирует конструктор без передачи значений и приведение к json
     *
     * @param VatType $type Тип НДС
     * @param float $sum Исходная сумма
     * @throws Exception
     * @dataProvider providerVatsSet
     * @covers       \AtolOnline\Entities\Vat
     * @covers       \AtolOnline\Entities\Vat::setType
     * @covers       \AtolOnline\Entities\Vat::getType
     * @covers       \AtolOnline\Entities\Vat::setSum
     * @covers       \AtolOnline\Entities\Vat::getSum
     * @covers       \AtolOnline\Entities\Vat::getCalculated
     * @covers       \AtolOnline\Entities\Vat::jsonSerialize
     */
    public function testConstructor(VatType $type, float $sum): void
    {
        $vat = new Vat($type, $sum);
        $this->assertIsAtolable($vat, [
            'type' => $vat->getType(),
            'sum' => $vat->getCalculated(),
        ]);
        $this->assertSame($type, $vat->getType());
        $this->assertSame($sum, $vat->getSum());
    }

    /**
     * Тестирует расчёт суммы НДС от суммы 100+20р и 100-20р
     *
     * @dataProvider providerVatsAdd
     * @param VatType $type Тип НДС
     * @param float $after_plus Результат после +20р
     * @param float $after_minus Результат после -20р
     * @covers       \AtolOnline\Entities\Vat::addSum
     * @covers       \AtolOnline\Entities\Vat::getCalculated
     */
    public function testVatAdd(VatType $type, float $after_plus, float $after_minus)
    {
        $vat = (new Vat($type, 100))->addSum(20); // 120р
        $this->assertSame($after_plus, $vat->getCalculated());
        $vat->addSum(-20); // 100р
        $this->assertSame($after_minus, $vat->getCalculated());
    }
}
