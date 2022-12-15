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

use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при ошибке валидации перечислимых значений
 */
class InvalidEnumValueException extends AtolException
{
    /**
     * Конструктор
     *
     * @param string $enum
     * @param mixed $value
     * @param string $message
     * @param array $ffd_tags
     */
    #[Pure]
    public function __construct(string $enum, mixed $value, string $message = '', array $ffd_tags = [])
    {
        $own_message = empty($value)
            ? "Значение $enum не может быть пустым."
            : "Некорректное значение $enum::$value.";
        parent::__construct($message ?: $own_message, $ffd_tags /*?: static::$ffd_tags*/);
    }
}
