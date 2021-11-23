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
     * @param float $max
     */
    public function __construct(float $value, string $message = '', float $max = 0)
    {
        $message = ($message ?: $this->message) . ': ' . $value;
        if ($max > 0 || $this->max > 0) {
            $message .= ' (макс. = ' . ($max ?? $this->max) . ', фактически = ' . $value . ')';
        }
        parent::__construct($message);
    }
}
