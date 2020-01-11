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
 * Исключение, возникающее при попытке указать слишком длинный телефон
 *
 * @package AtolOnline\Exceptions
 */
class AtolUserdataTooLongException extends AtolException
{
    /**
     * AtolUserdataTooLongException constructor.
     *
     * @param                 $data
     * @param                 $max
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($data, $max, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Слишком длинный дополнительный реквизит (макс. длина '.$max.', фактически '.strlen($data).'): '.$data;
        parent::__construct($message, $code, $previous);
    }
}