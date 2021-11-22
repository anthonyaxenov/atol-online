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
     * @var int Максимальная длина строки
     */
    protected int $max = 0;

    /**
     * Конструктор
     *
     * @param string $value
     * @param string $message
     * @param int $max
     */
    public function __construct(string $value, string $message = '', int $max = 0)
    {
        $message = ($message ?: $this->message) . ': '. $value;
        if ($max > 0 || $this->max > 0) {
            $message .= ' (макс. = ' . ($max ?? $this->max) . ', фактически = ' . mb_strlen($value) . ')';
        }
        parent::__construct($message);
    }
}
