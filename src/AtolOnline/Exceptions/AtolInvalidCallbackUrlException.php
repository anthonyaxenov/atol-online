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
 * Исключение, возникающее при попытке указать невалидный callback_url
 *
 * @package AtolOnline\Exceptions
 */
class AtolInvalidCallbackUrlException extends AtolException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Invalid callback URL';
}