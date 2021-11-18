<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Tests;

use AtolOnline\{
    Constants\VatTypes,
    Entities\Vat};

/**
 * Class VatTest
 */
class VatTestTodo extends BasicTestCase
{
    /**
     * Тестирует каждый тип ставки НДС
     *
     * @dataProvider vatProvider
     * @param string $vat_type Тип НДС
     * @param float $sum Исходная сумма
     * @param float $expected_set Ожидаемый результат после установки суммы
     * @param float $expected_add Ожидаемый результат после прибавления 20р
     */
    public function testVat(string $vat_type, float $sum, float $expected_set, float $expected_add)
    {
        $vat = new Vat($vat_type);
        $this->assertEquals(0, $vat->getFinalSum(), 'Test ' . $vat_type . ' | 1 step');
        $vat->setSum($sum);
        $this->assertEquals($expected_set, $vat->getFinalSum(), 'Test ' . $vat_type . ' | 2 step');
        $vat->addSum(20);
        $this->assertEquals($expected_add, $vat->getFinalSum(), 'Test ' . $vat_type . ' | 3 step');
        $vat->addSum(-20);
    }

    /**
     * Провайдер данных для тестирования разных типов ставок НДС
     *
     * @return array
     */
    public function vatProvider()
    {
        return [
            [VatTypes::NONE, 100, 0, 0],
            [VatTypes::VAT0, 100, 0, 0],
            [VatTypes::VAT10, 100, 9.09, 10.9],
            [VatTypes::VAT18, 100, 15.25, 18.3],
        ];
    }
}