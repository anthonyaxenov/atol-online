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
 * Исключение, возникающее при ошибке валидации UUID
 *
 * @package AtolOnline\Exceptions
 */
class AtolUuidValidateException extends AtolException
{
    /**
     * AtolUuidValidateException constructor.
     *
     * @param                 $uuid
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($uuid, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Некорректный UUID: '.$uuid;
        parent::__construct($message, $code, $previous);
    }
}