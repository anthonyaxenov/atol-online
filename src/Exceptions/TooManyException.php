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

use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при попытке указать слишком большое количество чего-либо
 */
class TooManyException extends AtolException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Слишком большое количество';

    /**
     * @var float Максимальное количество
     */
    protected float $max = 0;

    /**
     * Конструктор
     *
     * @param float $value
     * @param string $message
     * @param float|null $max
     */
    #[Pure]
    public function __construct(float $value, string $message = '', ?float $max = null)
    {
        parent::__construct(
            ($message ?: $this->message) . (((float)$max > 0 || (float)$this->max > 0) ?
                ' (макс = ' . ($max ?? $this->max) . ', фактически = ' . $value . ')' : '')
        );
    }
}
