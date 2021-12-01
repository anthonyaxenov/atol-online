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
 * Константы, определяющие типы налогообложения
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 35
 */
final class SnoTypes extends Enum
{
    /**
     * Общая СН
     */
    const OSN = 'osn';

    /**
     * Упрощенная СН (доходы)
     */
    const USN_INCOME = 'usn_income';

    /**
     * Упрощенная СН (доходы минус расходы)
     */
    const USN_INCOME_OUTCOME = 'usn_income_outcome';

    /**
     * Единый налог на вмененный доход
     */
    const ENDV = 'envd';

    /**
     * Единый сельскохозяйственный налог
     */
    const ESN = 'esn';

    /**
     * Патентная СН
     */
    const PATENT = 'patent';

    /**
     * @inheritDoc
     */
    public static function getFfdTags(): array
    {
        return [Ffd105Tags::COMPANY_SNO];
    }
}
