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
 * Исключение, возникающее при попытке указать ИНН некорректной длины
 */
class InvalidInnLengthException extends AtolException
{
    protected array $ffd_tags = [1016, 1018, 1226, 1228];

    /**
     * Конструктор
     *
     * @param string $inn
     * @param string $message
     */
    public function __construct(string $inn = '', string $message = '')
    {
        parent::__construct(
            $message ?: 'Длина ИНН должна быть 10 или 12 цифр, фактически - ' . strlen($inn),
        );
    }
}
