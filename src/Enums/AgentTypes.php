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

use AtolOnline\Enum;

/**
 * Константы, определяющие типы агента
 *
 * Тег ФФД - 1057
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 18 (agent_info)
 */
final class AgentTypes extends Enum
{
    /**
     * Банковский платёжный агент
     */
    const BANK_PAYING_AGENT = 'bank_paying_agent';

    /**
     * Банковский платёжный субагент
     */
    const BANK_PAYING_SUBAGENT = 'bank_paying_subagent';

    /**
     * Платёжный агент
     */
    const PAYING_AGENT = 'paying_agent';

    /**
     * Платёжный субагент
     */
    const PAYING_SUBAGENT = 'paying_subagent';

    /**
     * Поверенный
     */
    const ATTRONEY = 'attorney';

    /**
     * Комиссионер
     */
    const COMMISSION_AGENT = 'commission_agent';

    /**
     * Другой тип агента
     */
    const ANOTHER = 'another';

    /**
     * @return int[] Возвращает массив тегов ФФД
     */
    public static function getFfdTags(): array
    {
        return [1057];
    }
}
