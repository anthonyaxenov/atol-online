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

use AtolOnline\Constraints;

/**
 * Исключение, возникающее при попытке указать слишком длинный код товара
 */
class TooLongItemCodeException extends TooLongException
{
    protected float $max = Constraints::MAX_LENGTH_ITEM_CODE;

    /**
     * Конструктор
     *
     * @param string $name
     * @param string $code
     */
    public function __construct(string $name, string $code)
    {
        parent::__construct($code, "Слишком длинный код товара '$name'");
    }
}
