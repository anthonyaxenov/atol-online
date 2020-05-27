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
 * Исключение, возникающее при работе с невалидным JSON
 *
 * @package AtolOnline\Exceptions
 */
class AtolInvalidJsonException extends AtolException
{
    /**
     * AtolInnWrongLengthException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: 'Invalid JSON: ['.json_last_error().'] '.json_last_error_msg(), $code, $previous);
    }
}