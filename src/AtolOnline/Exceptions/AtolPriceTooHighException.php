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
 * Исключение, возникающее при попытке указать слишком высокую цену (сумму)
 *
 * @package AtolOnline\Exceptions
 */
class AtolPriceTooHighException extends AtolException
{
    /**
     * AtolPriceTooHighException constructor.
     *
     * @param                 $price
     * @param                 $max
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($price, $max, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Слишком большая сумма (макс. '.$max.'): '.$price;
        parent::__construct($message, $code, $previous);
    }
}