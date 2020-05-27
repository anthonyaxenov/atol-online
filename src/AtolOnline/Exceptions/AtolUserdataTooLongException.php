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
 * Исключение, возникающее при попытке указать слишком длинный дополнительный реквизит
 *
 * @package AtolOnline\Exceptions
 */
class AtolUserdataTooLongException extends AtolTooLongException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1191,
    ];
    
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'User data is too long';
}