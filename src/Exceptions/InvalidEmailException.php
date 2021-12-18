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
 * Исключение, возникающее при ошибке валидации email
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 17
 */
class InvalidEmailException extends AtolException
{
    protected array $ffd_tags = [
        Ffd105Tags::CLIENT_PHONE_EMAIL,
        Ffd105Tags::COMPANY_EMAIL,
    ];

    /**
     * Конструктор
     *
     * @param string $email
     */
    #[Pure]
    public function __construct(string $email = '')
    {
        parent::__construct("Невалидный email: '$email'");
    }
}
