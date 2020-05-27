<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Exceptions;

/**
 * Исключение, возникающее при попытке указать слишком длинное имя кассира
 *
 * @package AtolOnline\Exceptions
 */
class AtolCashierTooLongException extends AtolTooLongException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1021,
    ];
    
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Cashier name is too long';
}