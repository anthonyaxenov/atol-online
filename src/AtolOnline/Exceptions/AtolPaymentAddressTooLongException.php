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
 * Исключение, возникающее при попытке указать слишком длинный платёжный адрес
 *
 * @package AtolOnline\Exceptions
 */
class AtolPaymentAddressTooLongException extends AtolException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1187,
    ];
    
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Payment address is too long';
}