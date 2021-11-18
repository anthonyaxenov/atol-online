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

use Throwable;

/**
 * Исключение, возникающее при попытке указать слишком большое количество чего-либо
 */
class BasicTooManyException extends AtolException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Quantity is too high';

    /**
     * AtolTooManyException constructor.
     *
     * @param float $quantity
     * @param float $max
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(float $quantity, float $max, $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message ?: $this->message . ' (max - ' . $max . ', actual - ' . $quantity . ')',
            $code,
            $previous
        );
    }
}