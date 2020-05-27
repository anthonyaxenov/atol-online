<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Exceptions;

use Throwable;

/**
 * Исключение, возникающее при попытке указать ИНН некорректной длины
 *
 * @package AtolOnline\Exceptions
 */
class AtolInnWrongLengthException extends AtolException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1016,
        1018,
        1226,
        1228,
    ];
    
    /**
     * AtolInnWrongLengthException constructor.
     *
     * @param                 $inn
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($inn, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: 'INN length must be 10 or 12 digits only, but actual is '.
            (function_exists('mb_strlen') ? mb_strlen($inn) : strlen($inn)).')', $code, $previous);
    }
}