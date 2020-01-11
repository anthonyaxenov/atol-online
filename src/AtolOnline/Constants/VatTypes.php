<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Constants;

/**
 * Константы, определяющие типы ставок НДС
 *
 * @package AtolOnline\Constants
 */
class VatTypes
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
