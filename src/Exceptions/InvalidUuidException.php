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

/**
 * Исключение, возникающее при ошибке валидации UUID
 */
class InvalidUuidException extends AtolException
{
    /**
     * Конструктор
     *
     * @param                 $uuid
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(?string $uuid = null)
    {
        parent::__construct('Невалидный UUID' . ($uuid ? ': ' . $uuid : ''));
    }
}
