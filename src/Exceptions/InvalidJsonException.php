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

use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при работе с невалидным JSON
 */
class InvalidJsonException extends AtolException
{
    /**
     * Конструктор
     */
    #[Pure]
    public function __construct()
    {
        parent::__construct('[' . json_last_error() . '] ' . json_last_error_msg());
    }
}
