<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Tests;

use AtolOnline\Helpers;

/**
 * Набор тестов для проверки работы функций-хелперов
 */
class HelpersTest extends BasicTestCase
{
    /**
     * Провайдер копеек для перевода в рубли
     *
     * @return array<array<int|null, float>>
     */
    public function providerKopeksToRubles(): array
    {
        return [
            [null, 0],
            [0, 0],
            [1, 0.01],
            [12, 0.12],
            [123, 1.23],
            [1234, 12.34],
            [12345, 123.45],
            [-1, 0.01],
            [-12, 0.12],
            [-123, 1.23],
            [-1234, 12.34],
            [-12345, 123.45],
        ];
    }

    /**
     * Провайдер рублей для перевода в копейки
     *
     * @return array<array<float|null, int>>
     */
    public function providerRublesToKopeks(): array
    {
        return [
            [null, 0],
            [0, 0],
            [0.01, 1],
            [0.12, 12],
            [1.23, 123],
            [12.34, 1234],
            [123.45, 12345],
            [-0.01, 1],
            [-0.12, 12],
            [-1.23, 123],
            [-12.34, 1234],
            [-123.45, 12345],
        ];
    }

    /**
     * Провайдер для тестирования генерации рандомной строки
     *
     * @return array<array<int, int>>
     */
    public function providerRandomStr(): array
    {
        return [
            [0, 0],
            [1, 1],
            [5, 5],
            [-1, 1],
            [-5, 5],
        ];
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Тестирует перевод копеек в рубли
     *
     * @dataProvider providerKopeksToRubles
     * @covers       \AtolOnline\Helpers::toRub
     */
    public function testKopeksToRubles(?int $kopeks, float $rubles): void
    {
        $result = Helpers::toRub($kopeks);
        $this->assertIsFloat($result);
        $this->assertSame($result, $rubles);
    }

    /**
     * Тестирует перевод копеек в рубли
     *
     * @dataProvider providerRublesToKopeks
     * @covers       \AtolOnline\Helpers::toKop
     */
    public function testRublesToKopeks(?float $rubles, int $kopeks): void
    {
        $result = Helpers::toKop($rubles);
        $this->assertIsInt($result);
        $this->assertSame($result, $kopeks);
    }

    /**
     * Тестирует длину рандомной строки
     *
     * @param int $input
     * @param int $output
     * @dataProvider providerRandomStr
     */
    public function testRandomString(int $input, int $output): void
    {
        $result = Helpers::randomStr($input);
        $this->assertIsString($result);
        $this->assertSame($output, strlen($result));
        // тестировать на наличие цифр быссмысленно
    }
}
