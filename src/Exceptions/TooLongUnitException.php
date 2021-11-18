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
 * Исключение, возникающее при попытке указать слишком длинный телефон
 */
class TooLongUnitException extends BasicTooLongException
{
    /**
     * @inheritDoc
     */
    protected array $ffd_tags = [
        1197,
    ];

    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Measurement unit is too long';
}