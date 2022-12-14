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
 * Исключение, возникающее при попытке указать предмету расчёта отрицательный акциз
 */
class NegativeItemExciseException extends AtolException
{
    protected array $ffd_tags = [Ffd105Tags::ITEM_EXCISE];

    /**
     * Конструктор
     *
     * @param string $name
     * @param float $excise
     */
    #[Pure]
    public function __construct(string $name, float $excise)
    {
        parent::__construct("Предмет расчёта '$name' не может иметь отрицательный акциз $excise");
    }
}
