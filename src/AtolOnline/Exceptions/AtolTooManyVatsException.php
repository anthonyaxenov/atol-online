<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Exceptions;

/**
 * Исключение, возникающее при попытке добавить слишком много ставок НДС в массив
 *
 * @package AtolOnline\Exceptions
 */
class AtolTooManyVatsException extends AtolTooManyException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
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