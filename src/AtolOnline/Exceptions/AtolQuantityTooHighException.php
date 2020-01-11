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
 * Исключение, возникающее при попытке указать слишком большое количество
 *
 * @package AtolOnline\Exceptions
 */
class AtolQuantityTooHighException extends AtolException
{
    /**
     * AtolQuantityTooHighException constructor.
     *
     * @param                 $quantity
     * @param                 $max
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($quantity, $max, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Слишком большое количество (макс. '.$max.'): '.$quantity;
        parent::__construct($message, $code, $previous);
    }
}