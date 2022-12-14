<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types=1);

namespace AtolOnline\Exceptions;

use AtolOnline\Ffd105Tags;
use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при ошибке валидации номера телефона
 */
class InvalidPhoneException extends AtolException
{
    protected array $ffd_tags = [
        Ffd105Tags::CLIENT_PHONE_EMAIL,
        Ffd105Tags::PAGENT_PHONE,
        Ffd105Tags::RPO_PHONES,
        Ffd105Tags::MTO_PHONES,
        Ffd105Tags::SUPPLIER_PHONES,
    ];

    /**
     * Конструктор
     *
     * @param string $phone
     */
    #[Pure]
    public function __construct(string $phone = '')
    {
        parent::__construct("Невалидный номер телефона: '$phone'");
    }
}
