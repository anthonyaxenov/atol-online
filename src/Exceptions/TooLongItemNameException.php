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

use AtolOnline\Constants\{
    Constraints,
    Ffd105Tags
};

/**
 * Исключение, возникающее при попытке указать слишком длинное имя
 */
class TooLongItemNameException extends TooLongException
{
    protected $message = 'Слишком длинное наименование предмета расчёта';
    protected float $max = Constraints::MAX_LENGTH_ITEM_NAME;
    protected array $ffd_tags = [Ffd105Tags::ITEM_NAME];
}
