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
 * Исключение, возникающее при попытке добавить слишком много платежей в массив
 *
 * @package AtolOnline\Exceptions
 */
class AtolTooManyPaymentsException extends AtolTooManyException
{
    /**
     * @inheritDoc
     */
    protected $ffd_tags = [
        1031,
        1081,
        1215,
        1217,
    ];
    
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Too many payments';
}