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
use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при попытке указать некорректную дату коррекции
 */
class InvalidCorrectionDateException extends AtolException
{
    protected array $ffd_tags = [Ffd105Tags::CORRECTION_DATE];

    /**
     * Конструктор
     *
     * @param string $date
     * @param string $message
     */
    #[Pure]
    public function __construct(string $date = '', string $message = '')
    {
        parent::__construct("Ошибка даты документа коррекции '$date': " . $message);
    }
}
