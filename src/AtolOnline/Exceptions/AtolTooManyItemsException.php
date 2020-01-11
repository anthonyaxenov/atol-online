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
 * Исключение, возникающее при попытке добавить слишком много предметов расчёта в массив
 *
 * @package AtolOnline\Exceptions
 */
class AtolTooManyItemsException extends AtolException
{
    /**
     * AtolTooManyItemsException constructor.
     *
     * @param int            $max
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($max, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Слишком много предметов расчёта (макс. '.$max.')';
        parent::__construct($message, $code, $previous);
    }
}