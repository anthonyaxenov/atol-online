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
 * Исключение, возникающее при попытке указать слишком высокую цену (сумму)
 *
 * @package AtolOnline\Exceptions
 */
class AtolPriceTooHighException extends AtolTooManyException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1079,
    ];
    
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Price is too high';
}