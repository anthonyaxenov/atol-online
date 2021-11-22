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

/**
 * Исключение, возникающее при попытке указать слишком длинный адрес места расчётов
 */
class TooLongPaymentAddressException extends TooLongException
{
    protected $message = 'Слишком длинный адрес места расчётов';
    protected int $max = Constraints::MAX_LENGTH_PAYMENT_ADDRESS;
    protected array $ffd_tags = [1187];
}
