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

use AtolOnline\Enums\Enum;

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
    public function __construct(string $enum, mixed $value, string $message = '', array $ffd_tags = [])
    {
        /** @var $enum Enum */
        $own_message = (
            empty($value)
                ? "Значение из $enum не может быть пустым."
                : "Некорректное значение $enum::$value."
            ) . " Допустимые значения: " . implode(', ', $enum::toArray());
        parent::__construct($message ?: $own_message, $ffd_tags ?: $enum::getFfdTags());
    }
}
