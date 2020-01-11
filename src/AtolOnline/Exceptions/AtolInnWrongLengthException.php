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
     * AtolInnWrongLengthException constructor.
     *
     * @param                 $inn
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($inn, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Длина ИНН должна быть 10 или 12 цифр, фактически '.strlen($inn).': '.$inn;
        parent::__construct($message, $code, $previous);
    }
}