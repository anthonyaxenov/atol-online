<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Enums;

use AtolOnline\Exceptions\InvalidEnumValueException;

/**
 * Расширение класса перечисления
 */
abstract class Enum extends \MyCLabs\Enum\Enum
{
    /**
     * @inheritDoc
     * @throws InvalidEnumValueException
     */
    public static function isValid($value)
    {
        return parent::isValid($value)
            ?: throw new InvalidEnumValueException(static::class, $value);
    }

    /**
     * Возвращает массив тегов ФФД
     *
     * @return int[]
     */
    abstract public static function getFfdTags(): array;
}
