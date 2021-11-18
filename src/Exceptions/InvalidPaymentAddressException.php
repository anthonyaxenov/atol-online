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

use Throwable;

/**
 * Исключение, возникающее при попытке указать некорректный платёжный адрес
 */
class InvalidPaymentAddressException extends AtolException
{
    /**
     * @inheritDoc
     */
    protected array $ffd_tags = [
        1187,
    ];

    /**
     * Конструктор
     *
     * @param string $address
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($address = '', $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message ?: "Wrong payment address: '$address'",
            $code,
            $previous
        );
    }
}