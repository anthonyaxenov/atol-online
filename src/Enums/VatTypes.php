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
 * Константы, определяющие типы ставок НДС
 *
 * Теги ФФД: 1199, 1105, 1104, 1103, 1102, 1107, 1106
 */
final class VatTypes extends Enum
{
    /**
     * Без НДС
     */
    const NONE = 'none';

    /**
     * НДС 0%
     */
    const VAT0 = 'vat0';

    /**
     * НДС 10%
     */
    const VAT10 = 'vat10';

    /**
     * НДС 18%
     */
    const VAT18 = 'vat18';

    /**
     * НДС 20%
     */
    const VAT20 = 'vat20';

    /**
     * НДС 10/110%
     */
    const VAT110 = 'vat110';

    /**
     * НДС 18/118%
     */
    const VAT118 = 'vat118';

    /**
     * НДС 20/120%
     */
    const VAT120 = 'vat120';
}
