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

/**
 * Исключение, возникающее при ошибке валидации email
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 17
 */
class InvalidEmailException extends AtolException
{
    protected array $ffd_tags = [1008, 1117];

    /**
     * Конструктор
     *
     * @param string $email
     */
    public function __construct(string $email = '')
    {
        parent::__construct("Невалидный email: '$email'");
    }
}
