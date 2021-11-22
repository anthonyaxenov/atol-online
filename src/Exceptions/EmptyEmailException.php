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
 * Исключение, возникающее при попытке указать пустой email
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 17
 */
class EmptyEmailException extends AtolException
{
    protected $message = 'Email не может быть пустым';
    protected array $ffd_tags = [1008, 1117];
}
