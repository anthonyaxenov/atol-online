<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Enums;

use AtolOnline\Constants\Ffd105Tags;

/**
 * Константы, определяющие виды оплат
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 35
 */
final class PaymentTypes extends Enum
{
    /**
     * Расчёт наличными
     */
    const CASH = 0;

    /**
     * Расчёт безналичными
     */
    const ELECTRON = 1;

    /**
     * Предварительная оплата (зачёт аванса)
     */
    const PREPAID = 2;

    /**
     * Предварительная оплата (кредит)
     */
    const CREDIT = 3;

    /**
     * Иная форма оплаты (встречное предоставление)
     */
    const OTHER = 4;

    /**
     * Расширенный типы оплаты (5)
     * Для каждого фискального типа оплаты можно указать расширенный тип оплаты
     */
    const ADD_5 = 5;

    /**
     * Расширенный типы оплаты (6)
     * Для каждого фискального типа оплаты можно указать расширенный тип оплаты
     */
    const ADD_6 = 6;

    /**
     * Расширенный типы оплаты (7)
     * Для каждого фискального типа оплаты можно указать расширенный тип оплаты
     */
    const ADD_7 = 7;

    /**
     * Расширенный типы оплаты (8)
     * Для каждого фискального типа оплаты можно указать расширенный тип оплаты
     */
    const ADD_8 = 8;

    /**
     * Расширенный типы оплаты (9)
     * Для каждого фискального типа оплаты можно указать расширенный тип оплаты
     */
    const ADD_9 = 9;

    /**
     * @inheritDoc
     */
    public static function getFfdTags(): array
    {
        return [
            Ffd105Tags::PAYMENT_TYPE_CASH,
            Ffd105Tags::PAYMENT_TYPE_ELECTRON,
            Ffd105Tags::PAYMENT_TYPE_PREPAID,
            Ffd105Tags::PAYMENT_TYPE_CREDIT,
            Ffd105Tags::PAYMENT_TYPE_OTHER,
        ];
    }
}
