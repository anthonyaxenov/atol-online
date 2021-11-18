<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline;

/**
 * Константы, определяющие параметры тестовой среды для ФФД 1.05
 *
 * @see https://online.atol.ru/files/ffd/test_sreda.txt Параметры настройки тестовых сред
 */
class TestEnvParams
{
    /**
     * Возвращает данные для работы с тестовой средой АТОЛ Онлайн ФФД 1.05
     *
     * @return string[]
     */
    public static function FFD105(): array
    {
        return [
            'endpoint' => 'https://testonline.atol.ru/possystem/v4/',
            'company_name' => 'АТОЛ',
            'inn' => '5544332219',
            'payment_address' => 'https://v4.online.atol.ru',
            'group' => 'v4-online-atol-ru_4179',
            'login' => 'v4-online-atol-ru',
            'password' => 'iGFFuihss',
            'endpoint_ofd' => 'https://consumer.1-ofd-test.ru/v1',
        ];
    }

    /**
     * Возвращает данные для работы с тестовой средой АТОЛ Онлайн ФФД 1.2
     *
     * @return string[]
     */
    public static function FFD12(): array
    {
        return [
            'endpoint' => 'https://testonline.atol.ru/possystem/v5/',
            'company_name' => 'АТОЛ',
            'inn' => '5544332219',
            'payment_address' => 'https://v5.online.atol.ru',
            'group' => 'v5-online-atol-ru_5179',
            'login' => 'v5-online-atol-ru',
            'password' => 'zUr0OxfI',
            'endpoint_ofd' => '',
        ];
    }
}