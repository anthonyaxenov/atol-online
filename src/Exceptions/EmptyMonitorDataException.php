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
 * Исключение, возникающее при попытке создать объект ККТ без данных от монитора
 */
class EmptyMonitorDataException extends AtolException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Cannot create KKT entity without data from monitor';
}