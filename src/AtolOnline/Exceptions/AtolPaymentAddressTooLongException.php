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
 * Исключение, возникающее при попытке указать слишком длинный платёжный адрес
 *
 * @package AtolOnline\Exceptions
 */
class AtolPaymentAddressTooLongException extends AtolException
{
    /**
     * AtolPaymentAddressTooLongException constructor.
     *
     * @param                 $address
     * @param                 $max
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($address, $max, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Слишком длинный адрес (макс. длина '.$max.', фактически '.strlen($address).'): '.$address;
        parent::__construct($message, $code, $previous);
    }
}