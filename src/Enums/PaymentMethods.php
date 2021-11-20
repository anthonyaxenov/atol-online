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

use MyCLabs\Enum\Enum;

/**
 * Константы, определяющие признаки способов расчёта
 *
 * Тег ФФД -  1214
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 22 (payment_method)
 */
final class PaymentMethods extends Enum
{
    /**
     * Предоплата 100% до передачи предмета расчёта
     */
    const FULL_PREPAYMENT = 'full_prepayment';

    /**
     * Частичная предоплата до передачи предмета расчёта
     */
    const PREPAYMENT = 'prepayment';

    /**
     * Аванс
     */
    const ADVANCE = 'advance';

    /**
     * Полная оплата с учётом аванса/предоплаты в момент передачи предмета расчёта
     */
    const FULL_PAYMENT = 'full_payment';

    /**
     * Частичный расчёт в момент передачи предмета расчёта (дальнейшая оплата в кредит)
     */
    const PARTIAL_PAYMENT = 'partial_payment';

    /**
     * Передача предмета расчёта в кредит
     */
    const CREDIT = 'credit';

    /**
     * Оплата кредита
     */
    const CREDIT_PAYMENT = 'credit_payment';
}