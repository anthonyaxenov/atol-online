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
 * Исключение, возникающее при попытке указать слишком большое количество чего-либо
 *
 * @package AtolOnline\Exceptions
 */
class AtolTooManyException extends AtolException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Quantity is too high';
    
    /**
     * AtolTooManyException constructor.
     *
     * @param                 $quantity
     * @param                 $max
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($quantity, $max, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $this->message.' (max - '.$max.', actual - '.$quantity.')';
        parent::__construct($message, $code, $previous);
    }
}