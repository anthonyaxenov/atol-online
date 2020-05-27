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
 * Исключение, возникающее при попытке указать пустой email
 *
 * @package AtolOnline\Exceptions
 */
class AtolEmailEmptyException extends AtolException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1008,
        1117,
    ];
    
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Email cannot be empty';
}