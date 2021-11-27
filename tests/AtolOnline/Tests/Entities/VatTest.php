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
    Enums\VatTypes,
    Tests\BasicTestCase
};

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
            [VatTypes::NONE, 0],
            [VatTypes::VAT0, 0],
            [VatTypes::VAT10, 10],
            [VatTypes::VAT18, 18],
            [VatTypes::VAT20, 20],
            [VatTypes::VAT110, 9.09],
            [VatTypes::VAT118, 15.25],
            [VatTypes::VAT120, 16.67],
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
            [VatTypes::VAT10, 12, 10],
            [VatTypes::VAT18, 21.6, 18],
            [VatTypes::VAT20, 24, 20],
            [VatTypes::VAT110, 10.91, 9.09],
            [VatTypes::VAT118, 18.31, 15.25],
            [VatTypes::VAT120, 20, 16.67],
        ];
    }

    /**
     * Тестирует конструктор без передачи значений и приведение к json
     *
     * @param string $type Тип НДС
     * @param float $sum Исходная сумма
     * @dataProvider providerVatsSet
     * @covers       \AtolOnline\Entities\Vat
     * @covers       \AtolOnline\Entities\Vat::setType
     * @covers       \AtolOnline\Entities\Vat::getType
     * @covers       \AtolOnline\Entities\Vat::setSum
     * @covers       \AtolOnline\Entities\Vat::getSum
     * @covers       \AtolOnline\Entities\Vat::getCalculated
     * @covers       \AtolOnline\Entities\Vat::jsonSerialize
     */
    public function testConstructor(string $type, float $sum): void
    {
        $vat = new Vat($type, $sum);
        $this->assertAtolable($vat, [
            'type' => $vat->getType(),
            'sum' => $vat->getCalculated(),
        ]);
        $this->assertEquals($type, $vat->getType());
        $this->assertEquals($sum, $vat->getSum());
    }

    /**
     * Тестирует расчёт суммы НДС от исходной суммы 100+20р и 100-20р
     *
     * @dataProvider providerVatsAdd
     * @param string $type Тип НДС
     * @param float $after_plus Результат после +20р
     * @param float $after_minus Результат после -20р
     * @covers       \AtolOnline\Entities\Vat::addSum
     * @covers       \AtolOnline\Entities\Vat::getCalculated
     */
    public function testVatAdd(string $type, float $after_plus, float $after_minus)
    {
        $vat = (new Vat($type, 100))->addSum(20); // 120р
        $this->assertEquals($after_plus, $vat->getCalculated());
        $vat->addSum(-20); // 100р
        $this->assertEquals($after_minus, $vat->getCalculated());
    }
}
