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

use AtolOnline\Constants\Ffd105Tags;

/**
 * Исключение, возникающее при пустом наименовании дополнительного реквизита пользователя
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 32
 */
class EmptyAddUserPropValueException extends AtolException
{
    protected $message = 'Значение дополнительного реквизита пользователя не может быть пустым';
    protected array $ffd_tags = [Ffd105Tags::DOC_ADD_USER_PROP_VALUE];
}
