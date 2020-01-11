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
 * Исключение, возникающее при попытке указать некорректный тип документа
 *
 * @package AtolOnline\Exceptions
 */
class AtolWrongDocumentTypeException extends AtolException
{
    /**
     * AtolWrongDocumentTypeException constructor.
     *
     * @param                 $type
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct($type, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Некорректный тип документа: ожидался \'receipt\' или \'correction\', указан \''.$type.'\'';
        parent::__construct($message, $code, $previous);
    }
}