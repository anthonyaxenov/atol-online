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
 * Исключение, возникающее при попытке указать слишком длинную единицу измерения предмета расчёта
 */
class TooLongUnitException extends TooLongException
{
    protected $message = 'Слишком длинная единица измерения предмета расчёта';
    protected float $max = Constraints::MAX_LENGTH_MEASUREMENT_UNIT;
    protected array $ffd_tags = [Ffd105Tags::ITEM_MEASURE];
}
