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
 * Исключение, возникающее при попытке зарегистрировать документ без данных коррекции
 *
 * @package AtolOnline\Exceptions
 */
class AtolCorrectionInfoException extends AtolException
{
    /**
     * AtolNoCorrectionInfoException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'В документе отсутствуют данные коррекции';
        parent::__construct($message, $code, $previous);
    }
}