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
 * Константы, определяющие типы операций (чеков)
 */
final class ReceiptOperationTypes extends Enum
{
    /**
     * Приход (мы продали)
     */
    const SELL = 'sell';

    /**
     * Возврат прихода (нам вернули предмет расчёта, мы вернули средства)
     */
    const SELL_REFUND = 'sell_refund';

    /**
     * Коррекция прихода
     */
    const SELL_CORRECTION = 'sell_correction';

    /**
     * Расход (мы купили)
     */
    const BUY = 'buy';

    /**
     * Возврат расхода (мы вернули предмет расчёта, нам вернули средства)
     */
    const BUY_REFUND = 'buy_refund';

    /**
     * Коррекция прихода (догоняем неучтённые средства)
     */
    const BUY_CORRECTION = 'buy_correction';
}
