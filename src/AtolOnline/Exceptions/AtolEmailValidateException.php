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
 * Исключение, возникающее при ошибке валидации email
 *
 * @package AtolOnline\Exceptions
 */
class AtolEmailValidateException extends AtolException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1008,
        1117,
    ];
    
    /**
     * AtolEmailValidateException constructor.
     *
     * @param                 $email
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($email, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: 'Invalid email: '.$email, $code, $previous);
    }
}