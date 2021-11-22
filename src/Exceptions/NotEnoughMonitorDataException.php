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
 * Исключение, возникающее при попытке создать объект ККТ с неполными данными от монитора
 */
class NotEnoughMonitorDataException extends AtolException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Невозможно создать объект ККТ без следующих атрибутов: ';

    /**
     * Конструктор
     *
     * @param array $props_diff
     * @param string $message
     */
    public function __construct(array $props_diff, string $message = '')
    {
        parent::__construct($message ?: $this->message . implode(', ', $props_diff));
    }
}
