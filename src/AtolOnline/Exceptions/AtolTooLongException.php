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
 * Исключение, возникающее при попытке указать слишком длинное что-либо
 *
 * @package AtolOnline\Exceptions
 */
class AtolTooLongException extends AtolException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Parameter is too long';
    
    /**
     * AtolTooLongException constructor.
     *
     * @param                 $string
     * @param                 $max
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($string, $max, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: $this->message.' (max length - '.$max.', actual length - '.
            (function_exists('mb_strlen') ? mb_strlen($string) : strlen($string)).')', $code, $previous);
    }
}