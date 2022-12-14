<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types=1);

namespace AtolOnline\Enums;

/**
 * Константы, определяющие признаки способов расчёта
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 22
 */
enum PaymentMethod: string
{
    /**
     * Предоплата 100% до передачи предмета расчёта
     */
    case FULL_PREPAYMENT = 'full_prepayment';

    /**
     * Частичная предоплата до передачи предмета расчёта
     */
    case PREPAYMENT = 'prepayment';

    /**
     * Аванс
     */
    case ADVANCE = 'advance';

    /**
     * Полная оплата с учётом аванса/предоплаты в момент передачи предмета расчёта
     */
    case FULL_PAYMENT = 'full_payment';

    /**
     * Частичный расчёт в момент передачи предмета расчёта (дальнейшая оплата в кредит)
     */
    case PARTIAL_PAYMENT = 'partial_payment';

    /**
     * Передача предмета расчёта в кредит
     */
    case CREDIT = 'credit';

    /**
     * Оплата кредита
     */
    case CREDIT_PAYMENT = 'credit_payment';
}
