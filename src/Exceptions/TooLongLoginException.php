<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types=1);

namespace AtolOnline\Exceptions;

use AtolOnline\Constraints;

/**
 * Исключение, возникающее при попытке указать слишком длинный логин ККТ
 */
class TooLongLoginException extends TooLongException
{
    protected $message = 'Слишком длинный логин';
    protected float $max = Constraints::MAX_LENGTH_LOGIN;
}
