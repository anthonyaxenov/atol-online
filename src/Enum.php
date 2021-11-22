<?php

declare(strict_types = 1);

namespace AtolOnline;

use AtolOnline\Exceptions\InvalidEnumValueException;

/**
 * Расширение класса перечисления
 */
class Enum extends \MyCLabs\Enum\Enum
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
}
