<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Enums;

use MyCLabs\Enum\Enum;

/**
 * Константы, определяющие типы документов коррекции
 *
 * Тег ФФД -  1173
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 35 (correction_info)
 */
final class CorrectionTypes extends Enum
{
    /**
     * Самостоятельно
     */
    const SELF = 'self';

    /**
     * По предписанию
     */
    const INSTRUCTION = 'instruction';
}
