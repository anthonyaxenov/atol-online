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
 * Исключение, возникающее при попытке указать слишком длинное имя
 *
 * @package AtolOnline\Exceptions
 */
class AtolNameTooLongException extends AtolException
{
    /**
     * AtolNameTooLongException constructor.
     *
     * @param                 $name
     * @param                 $max
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($name, $max, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Слишком длинное имя/наименование (макс. длина '.$max.', фактически '.strlen($name).'): '.$name;
        parent::__construct($message, $code, $previous);
    }
}