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

/**
 * Константы, определяющие типы документов
 */
final class DocumentTypes extends Enum
{
    /**
     * Документ прихода, возврата прихода, расхода, возврата расхода
     */
    const RECEIPT = 'receipt';

    /**
     * Документ коррекции
     */
    const CORRECTION = 'correction';

    /**
     * @inheritDoc
     */
    public static function getFfdTags(): array
    {
        return [];
    }
}
