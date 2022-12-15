<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types=1);

namespace AtolOnline\Enums;

/**
 * Константы, определяющие типы налогообложения
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 35
 */
enum SnoType: string
{
    /**
     * Общая СН
     */
    case OSN = 'osn';

    /**
     * Упрощенная СН (доходы)
     */
    case USN_INCOME = 'usn_income';

    /**
     * Упрощенная СН (доходы минус расходы)
     */
    case USN_INCOME_OUTCOME = 'usn_income_outcome';

    /**
     * Единый налог на вмененный доход
     */
    case ENDV = 'envd';

    /**
     * Единый сельскохозяйственный налог
     */
    case ESN = 'esn';

    /**
     * Патентная СН
     */
    case PATENT = 'patent';
}
