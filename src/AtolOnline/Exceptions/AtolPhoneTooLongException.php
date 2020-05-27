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
 * Исключение, возникающее при попытке указать слишком длинный телефон
 *
 * @package AtolOnline\Exceptions
 */
class AtolPhoneTooLongException extends AtolTooLongException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1008,
        1073,
        1074,
        1075,
        1171,
    ];
    
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Phone is too long';
}