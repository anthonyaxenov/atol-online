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
 * Исключение, возникающее при попытке указать слишком длинный логин ККТ
 *
 * @package AtolOnline\Exceptions
 */
class AtolKktLoginTooLongException extends AtolTooLongException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'KKT login is too long';
}