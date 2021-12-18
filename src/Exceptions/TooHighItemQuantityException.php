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
 * Исключение, возникающее при попытке добавить слишком большое количество предмета расчёта
 */
class TooHighItemQuantityException extends TooManyException
{
    protected float $max = Constraints::MAX_COUNT_ITEM_QUANTITY;
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
        parent::__construct($quantity, "Слишком большое количество предмета расчёта '$name'");
    }
}
