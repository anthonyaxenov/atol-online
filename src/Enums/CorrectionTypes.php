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

use AtolOnline\Constants\Ffd105Tags;

/**
 * Константы, определяющие типы документов коррекции
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 35
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

    /**
     * @inheritDoc
     */
    public static function getFfdTags(): array
    {
        return [Ffd105Tags::CORRECTION_TYPE];
    }
}
