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
 * Исключение, возникающее при попытке получеиня слишком высокой стоимости предмета расчёта
 */
class TooHighItemSumException extends TooManyException
{
    protected array $ffd_tags = [Ffd105Tags::ITEM_SUM];
    protected float $max = Constraints::MAX_COUNT_ITEM_SUM;

    /**
     * Конструктор
     *
     * @param string $name
     * @param float $sum
     */
    public function __construct(string $name, float $sum)
    {
        parent::__construct($sum, "Слишком высокая стоимость предмета расчёта '$name'");
    }
}
