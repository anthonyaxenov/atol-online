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
 * Исключение, возникающее при попытке указать документу пустую коллекцию оплат
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 30
 */
class EmptyPaymentsException extends AtolException
{
    protected $message = 'Документ не может содержать пустую коллекцию оплат';
}