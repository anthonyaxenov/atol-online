<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Exceptions;

use AtolOnline\Api\KktResponse;
use Exception;
use Throwable;

/**
 * Исключение, возникающее при работе с АТОЛ Онлайн
 *
 * @package AtolOnline\Exceptions
 */
class AtolAuthFailedException extends Exception
{
    /**
     * AtolAuthFailedException constructor.
     *
     * @param \AtolOnline\Api\KktResponse $last_response
     * @param string                      $message
     * @param int                         $code
     * @param \Throwable|null             $previous
     */
    public function __construct(KktResponse $last_response, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $last_response->isValid()
            ? $message
            : '['.$last_response->error->code.'] '.$last_response->error->text.
            '. ERROR_ID: '.$last_response->error->error_ID.
            '. TYPE: '.$last_response->error->type;
        $code = $last_response->isValid() ? $code : $last_response->error->code;
        parent::__construct($message, $code, $previous);
    }
}