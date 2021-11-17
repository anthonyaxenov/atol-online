<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Exceptions;

use AtolOnline\Api\KktResponse;
use Exception;
use Throwable;

/**
 * Исключение, возникающее при неудачной авторизации
 */
class AuthFailedException extends Exception
{
    /**
     * Конструктор
     *
     * @param KktResponse $response
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(KktResponse $response, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = $response->isValid()
            ? $message
            : '[' . $response->error->code . '] ' . $response->error->text .
            '. ERROR_ID: ' . $response->error->error_id .
            '. TYPE: ' . $response->error->type;
        $code = $response->isValid() ? $code : $response->error->code;
        parent::__construct($message, $code, $previous);
    }
}
