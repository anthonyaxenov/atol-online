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
 * Исключение, возникающее при попытке установки слишком большой суммы оплаты
 */
class TooHighPaymentSumException extends TooManyException
{
    protected $message = 'Слишком высокый размер оплаты';
    protected array $ffd_tags = [
        Ffd105Tags::PAYMENT_TYPE_CASH,
        Ffd105Tags::PAYMENT_TYPE_CREDIT,
        Ffd105Tags::PAYMENT_TYPE_ELECTRON,
        Ffd105Tags::PAYMENT_TYPE_PREPAID,
        Ffd105Tags::PAYMENT_TYPE_OTHER,
    ];
    protected float $max = Constraints::MAX_COUNT_PAYMENT_SUM;
}
