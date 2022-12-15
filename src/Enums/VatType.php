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
 * Константы, определяющие типы ставок НДС
 */
enum VatType: string
{
    /**
     * Без НДС
     */
    case NONE = 'none';

    /**
     * НДС 0%
     */
    case VAT0 = 'vat0';

    /**
     * НДС 10%
     */
    case VAT10 = 'vat10';

    /**
     * НДС 18%
     */
    case VAT18 = 'vat18';

    /**
     * НДС 20%
     */
    case VAT20 = 'vat20';

    /**
     * НДС 10/110%
     */
    case VAT110 = 'vat110';

    /**
     * НДС 18/118%
     */
    case VAT118 = 'vat118';

    /**
     * НДС 20/120%
     */
    case VAT120 = 'vat120';
}
