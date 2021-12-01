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
 * Исключение, возникающее при ошибке валидации кода страны происхождения товара
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 29
 */
class InvalidOKSMCodeException extends AtolException
{
    protected array $ffd_tags = [Ffd105Tags::ITEM_COUNTRY_CODE];

    /**
     * Конструктор
     *
     * @param string $code
     */
    public function __construct(string $code)
    {
        parent::__construct('Невалидный код страны происхождения товара: ' . $code);
    }
}
