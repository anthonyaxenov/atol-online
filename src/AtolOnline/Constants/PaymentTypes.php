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
 * Константы, определяющие виды оплат. Тег ФФД - 1031, 1081, 1215, 1216, 1217.
 *
 * @package AtolOnline\Constants
 */
class PaymentTypes
{
    /**
     * Расчёт наличными. Тег ФФД - 1031.
     */
    const CASH = 0;
    
    /**
     * Расчёт безналичными. Тег ФФД - 1081.
     */
    const ELECTRON = 1;
    
    /**
     * Предварительная оплата (зачет аванса). Тег ФФД - 1215.
     */
    const PRE_PAID = 2;
    
    /**
     * Предварительная оплата (кредит). Тег ФФД - 1216.
     */
    const CREDIT = 3;
    
    /**
     * Иная форма оплаты (встречное предоставление). Тег ФФД - 1217.
     */
    const OTHER = 4;
    
    /**
     * Расширенный типы оплаты
     * Для каждого фискального типа оплаты можно указать расширенный тип оплаты
     */
    const ADD_5 = 5;
    const ADD_6 = 6;
    const ADD_7 = 7;
    const ADD_8 = 8;
    const ADD_9 = 9;
}
