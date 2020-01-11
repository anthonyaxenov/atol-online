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
 * Константы, определяющие типы операций (чеков)
 *
 * @package AtolOnline\Constants
 */
class ReceiptOperationTypes
{
    /**
     * Приход (мы продали)
     */
    const SELL = 'sell';
    
    /**
     * Возврат прихода (нам вернули предмет расчёта, мы вернули деньги)
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
     * Возврат расхода (мы вернули предмет расчёта, нам вернули деньги)
     */
    const BUY_REFUND = 'buy_refund';
    
    /**
     * Коррекция прихода
     */
    const BUY_CORRECTION = 'buy_correction';
}