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

use AtolOnline\Ffd105Tags;
use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при ошибке валидации кода таможенной декларации
 */
class InvalidDeclarationNumberException extends AtolException
{
    protected array $ffd_tags = [Ffd105Tags::ITEM_DECLARATION_NUMBER];

    /**
     * Конструктор
     *
     * @param string $code
     */
    #[Pure]
    public function __construct(string $code = '')
    {
        parent::__construct("Невалидный код таможенной декларации: '$code'");
    }
}
