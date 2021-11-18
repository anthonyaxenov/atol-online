<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Constants;

use MyCLabs\Enum\Enum;

/**
 * Константы, определяющие типы документов
 */
final class DocumentTypes extends Enum
{
    /**
     * Чек прихода, возврата прихода, расхода, возврата расхода
     */
    const RECEIPT = 'receipt';

    /**
     * Чек коррекции
     */
    const CORRECTION = 'correction';
}
