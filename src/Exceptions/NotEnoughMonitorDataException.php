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
 * Исключение, возникающее при попытке создать объект ККТ с неполными данными от монитора
 */
class NotEnoughMonitorDataException extends AtolException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Cannot create KKT entity without these properties: ';

    /**
     * Конструктор
     *
     * @param array $props_diff
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(array $props_diff, $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: $this->message . implode(', ', $props_diff), $code, $previous);
    }
}