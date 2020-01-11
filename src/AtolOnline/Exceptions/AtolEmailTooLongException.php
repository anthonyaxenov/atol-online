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
 * Исключение, возникающее при попытке указать слишком длинный email
 *
 * @package AtolOnline\Exceptions
 */
class AtolEmailTooLongException extends AtolException
{
    /**
     * AtolEmailTooLongException constructor.
     *
     * @param                 $email
     * @param                 $max
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($email, $max, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Слишком длинный email (макс. длина '.$max.', фактически '.strlen($email).'): '.$email;
        parent::__construct($message, $code, $previous);
    }
}