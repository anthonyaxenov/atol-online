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

use AtolOnline\Constants\DocumentTypes;
use Throwable;

/**
 * Исключение, возникающее при попытке указать некорректный тип документа
 */
class InvalidDocumentTypeException extends AtolException
{
    /**
     * Конструктор
     *
     * @param string $type
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($type = '', $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message ?: "Wrong document type: '$type'. Valid ones: " . implode(', ', DocumentTypes::toArray()),
            $code,
            $previous
        );
    }
}