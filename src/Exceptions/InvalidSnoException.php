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

use AtolOnline\Enums\SnoTypes;
use Throwable;

/**
 * Исключение, возникающее при попытке указать некорректную СНО
 */
class InvalidSnoException extends AtolException
{
    /**
     * @inheritDoc
     */
    protected array $ffd_tags = [
        1055,
    ];

    /**
     * Конструктор
     *
     * @param string $sno
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($sno = '', $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message ?: "Wrong SNO: '$sno'. Valid ones: " . implode(', ', SnoTypes::toArray()),
            $code,
            $previous
        );
    }
}