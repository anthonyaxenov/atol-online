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

/**
 * Исключение, возникающее при попытке указать некорректный адрес места расчётов
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 35
 */
class InvalidPaymentAddressException extends AtolException
{
    protected array $ffd_tags = [Ffd105Tags::COMPANY_PADDRESS];

    /**
     * Конструктор
     *
     * @param string $address
     * @param string $message
     */
    public function __construct($address = '', $message = "")
    {
        parent::__construct($message ?: "Некорректный адрес места расчётов: '$address'");
    }
}
