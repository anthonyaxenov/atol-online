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

use AtolOnline\Constants\Constraints;

/**
 * Исключение, возникающее при попытке добавить слишком много ставок НДС в документ
 */
class TooManyVatsException extends TooManyException
{
    protected $message = 'Слишком много ставок НДС в документе';
    protected float $max = Constraints::MAX_COUNT_DOC_VATS;
}
