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
 * Исключение, возникающее при попытке добавить слишком много ставок НДС в массив
 */
class TooManyVatsException extends BasicTooManyException
{
    /**
     * @inheritDoc
     */
    protected array $ffd_tags = [
        1102,
        1103,
        1104,
        1105,
        1106,
        1107,
    ];
    
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Too many vats';
}