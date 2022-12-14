<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types=1);

namespace AtolOnline\Exceptions;

use AtolOnline\Api\AtolResponse;
use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при неудачной авторизации
 */
class AuthFailedException extends Exception
{
    /**
     * Конструктор
     *
     * @param AtolResponse $response
     * @param string $message
     */
    #[Pure]
    public function __construct(AtolResponse $response, string $message = '')
    {
        parent::__construct(($message ?: 'Ошибка авторизации: ') . ': ' . $response);
    }
}
