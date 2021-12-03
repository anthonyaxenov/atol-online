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

/**
 * Исключение, возникающее при попытке указать слишком высокую цену (сумму) предмета расчёта
 */
class TooHighItemPriceException extends TooManyException
{
    protected array $ffd_tags = [Ffd105Tags::ITEM_PRICE];
    protected float $max = Constraints::MAX_COUNT_ITEM_PRICE;

    /**
     * Конструктор
     *
     * @param string $name
     * @param float $price
     */
    public function __construct(string $name, float $price)
    {
        parent::__construct($price, "Слишком высокая цена для предмета расчёта '$name'");
    }
}
