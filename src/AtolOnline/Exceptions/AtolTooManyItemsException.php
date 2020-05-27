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
 * Исключение, возникающее при попытке добавить слишком много предметов расчёта в массив
 *
 * @package AtolOnline\Exceptions
 */
class AtolTooManyItemsException extends AtolTooManyException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Too many items';
}