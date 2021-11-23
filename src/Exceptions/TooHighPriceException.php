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
 * Исключение, возникающее при попытке указать слишком высокую цену (сумму)
 */
class TooHighPriceException extends TooManyException
{
    protected $message = 'Слишком высокая цена';
    protected array $ffd_tags = [Ffd105Tags::ITEM_PRICE];
    protected float $max = Constraints::MAX_COUNT_ITEM_PRICE;
}
