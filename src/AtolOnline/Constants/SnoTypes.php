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
 * Константы, определяющие типы налогообложения
 *
 * @package AtolOnline\Constants
 */
class SnoTypes
{
    /**
     * Общая СН
     */
    const OSN = 'osn';
    
    /**
     * Упрощенная СН (доходы)
     */
    const USN_INCOME = 'usn_income';
    
    /**
     * Упрощенная СН (доходы минус расходы)
     */
    const USN_INCOME_OUTCOME = 'usn_income_outcome';
    
    /**
     * Единый налог на вмененный доход
     */
    const ENDV = 'envd';
    
    /**
     * Единый сельскохозяйственный налог
     */
    const ESN = 'esn';
    
    /**
     * Патентная СН
     */
    const PATENT = 'patent';
}