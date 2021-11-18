<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Exceptions;

/**
 * Исключение, возникающее при попытке добавить слишком много предметов расчёта в массив
 */
class TooManyItemsException extends BasicTooManyException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Too many items';
}