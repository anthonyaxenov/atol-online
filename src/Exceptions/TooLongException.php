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
 * Исключение, возникающее при попытке указать слишком длинное что-либо
 */
class TooLongException extends AtolException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Слишком длинное значение';

    /**
     * @var float Максимальная длина строки
     */
    protected float $max = 0;

    /**
     * Конструктор
     *
     * @param string $value
     * @param string $message
     * @param float $max
     */
    public function __construct(string $value, string $message = '', float $max = 0)
    {
        parent::__construct(
            ($message ?: $this->message) . ': ' . $value . (((float)$max > 0 || (float)$this->max > 0) ?
                ' (макс = ' . ($max ?: $this->max) . ', фактически = ' . mb_strlen($value) . ')' : '')
        );
    }
}
