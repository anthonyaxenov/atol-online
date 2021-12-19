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

use AtolOnline\Constants\Constraints;
use AtolOnline\Constants\Ffd105Tags;
use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при попытке указать слишком длинный код товара
 */
class TooLongItemCodeException extends TooLongException
{
    protected float $max = Constraints::MAX_LENGTH_ITEM_CODE;
    protected array $ffd_tags = [Ffd105Tags::ITEM_NOMENCLATURE_CODE];

    /**
     * Конструктор
     *
     * @param string $name
     * @param string $code
     */
    #[Pure]
    public function __construct(string $name, string $code)
    {
        parent::__construct($code, "Слишком длинный код товара '$name'");
    }
}
