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

use AtolOnline\Constants\Ffd105Tags;

/**
 * Исключение, возникающее при пустом номере документа коррекции
 */
class EmptyCorrectionNumberException extends AtolException
{
    protected $message = 'Номер документа коррекции не может быть пустым';
    protected array $ffd_tags = [Ffd105Tags::CORRECTION_DATE];
}
