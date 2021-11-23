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

use AtolOnline\Constants\{
    Constraints,
    Ffd105Tags
};

/**
 * Исключение, возникающее при попытке указать слишком длинную операцию для платёжного агента
 */
class TooLongPayingAgentOperationException extends TooLongException
{
    protected $message = 'Слишком длинное yаименование операции платёжного агента';
    protected float $max = Constraints::MAX_LENGTH_PAYING_AGENT_OPERATION;
    protected array $ffd_tags = [Ffd105Tags::PAGENT_PHONE];
}
