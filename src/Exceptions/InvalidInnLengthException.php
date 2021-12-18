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
 * Исключение, возникающее при попытке указать ИНН некорректной длины
 */
class InvalidInnLengthException extends AtolException
{
    protected array $ffd_tags = [
        Ffd105Tags::MTO_INN,
        Ffd105Tags::COMPANY_INN,
        Ffd105Tags::SUPPLIER_INN,
        Ffd105Tags::CLIENT_INN,
    ];

    /**
     * Конструктор
     *
     * @param string $inn
     * @param string $message
     */
    #[Pure]
    public function __construct(string $inn = '', string $message = '')
    {
        parent::__construct(
            $message ?: 'Длина ИНН должна быть 10 или 12 цифр, фактически - ' . strlen($inn),
        );
    }
}
