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
 * Исключение, возникающее при попытке указать слишком длинное имя
 *
 * @package AtolOnline\Exceptions
 */
class AtolNameTooLongException extends AtolTooLongException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1026,
        1030,
        1085,
        1225,
        1227,
    ];
    
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Name is too long';
}