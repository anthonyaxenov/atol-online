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

use AtolOnline\Ffd105Tags;
use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при попытке указать предмету расчёта отрицательное количество
 */
class NegativeItemQuantityException extends AtolException
{
    protected array $ffd_tags = [Ffd105Tags::ITEM_QUANTITY];

    /**
     * Конструктор
     *
     * @param string $name
     * @param float $quantity
     */
    #[Pure]
    public function __construct(string $name, float $quantity)
    {
        parent::__construct("Предмет расчёта '$name' не может иметь отрицательное количество $quantity");
    }
}
