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
 * Исключение, возникающее при попытке указать слишком длинный email
 *
 * @package AtolOnline\Exceptions
 */
class AtolEmailTooLongException extends AtolTooLongException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1008,
        1117,
    ];
    
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Email is is too long';
}