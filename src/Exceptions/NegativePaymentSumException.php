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

use AtolOnline\Constants\Ffd105Tags;
use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при попытке указать оплате отрицательную сумму
 */
class NegativePaymentSumException extends AtolException
{
    protected array $ffd_tags = [
        Ffd105Tags::PAYMENT_TYPE_CASH,
        Ffd105Tags::PAYMENT_TYPE_CREDIT,
        Ffd105Tags::PAYMENT_TYPE_ELECTRON,
        Ffd105Tags::PAYMENT_TYPE_PREPAID,
        Ffd105Tags::PAYMENT_TYPE_OTHER,
    ];

    /**
     * Конструктор
     *
     * @param float $sum
     */
    #[Pure]
    public function __construct(float $sum)
    {
        parent::__construct('Размер оплаты не может быть отрицательным: ' . $sum);
    }
}
