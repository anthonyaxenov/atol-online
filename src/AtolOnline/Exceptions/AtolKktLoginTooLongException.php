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
 * Исключение, возникающее при попытке указать слишком длинный логин ККТ
 *
 * @package AtolOnline\Exceptions
 */
class AtolKktLoginTooLongException extends AtolException
{
    /**
     * AtolKktLoginTooLongException constructor.
     *
     * @param                 $login
     * @param                 $max
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($login, $max, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Слишком длинный логин ККТ (макс.  длина '.$max.', фактически '.strlen($login).'): '.$login;
        parent::__construct($message, $code, $previous);
    }
}