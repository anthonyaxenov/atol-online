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

use Throwable;

/**
 * Исключение, возникающее при попытке указать ИНН некорректной длины
 */
class InvalidInnLengthException extends AtolException
{
    /**
     * @inheritDoc
     */
    protected array $ffd_tags = [
        1016,
        1018,
        1226,
        1228,
    ];

    /**
     * AtolInnWrongLengthException constructor.
     *
     * @param string $inn
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($inn = '', $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message ?: 'INN length must be 10 or 12 digits only, actual is ' . strlen($inn),
            $code,
            $previous
        );
    }
}