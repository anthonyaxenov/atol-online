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
 * Константы, определяющие типы ставок НДС
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

    /**
     * @inheritDoc
     */
    public static function getFfdTags(): array
    {
        return [
            Ffd105Tags::ITEM_VAT_TYPE,
            Ffd105Tags::DOC_VAT_TYPE_NONE,
            Ffd105Tags::DOC_VAT_TYPE_VAT0,
            Ffd105Tags::DOC_VAT_TYPE_VAT10,
            Ffd105Tags::DOC_VAT_TYPE_VAT20,
            Ffd105Tags::DOC_VAT_TYPE_VAT110,
            Ffd105Tags::DOC_VAT_TYPE_VAT120,
        ];
    }
}
