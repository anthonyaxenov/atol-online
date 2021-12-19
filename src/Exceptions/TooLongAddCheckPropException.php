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

use AtolOnline\Constants\{
    Constraints,
    Ffd105Tags};

/**
 * Исключение, возникающее при попытке указать слишком длинное наименование дополнительного реквизита чека
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 32
 */
class TooLongAddCheckPropException extends TooLongException
{
    protected $message = 'Слишком длинное наименование дополнительного реквизита чека';
    protected float $max = Constraints::MAX_LENGTH_ADD_CHECK_PROP;
    protected array $ffd_tags = [Ffd105Tags::DOC_ADD_CHECK_PROP_VALUE];
}
