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
 * Константы, определяющие типы операций (чеков)
 */
enum ReceiptOperationType: string
{
    /**
     * Приход (мы продали)
     */
    case SELL = 'sell';

    /**
     * Возврат прихода (нам вернули предмет расчёта, мы вернули средства)
     */
    case SELL_REFUND = 'sell_refund';

    /**
     * Коррекция прихода
     */
    case SELL_CORRECTION = 'sell_correction';

    /**
     * Расход (мы купили)
     */
    case BUY = 'buy';

    /**
     * Возврат расхода (мы вернули предмет расчёта, нам вернули средства)
     */
    case BUY_REFUND = 'buy_refund';

    /**
     * Коррекция прихода (догоняем неучтённые средства)
     */
    case BUY_CORRECTION = 'buy_correction';
}
