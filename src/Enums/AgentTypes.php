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
 * Константы, определяющие типы агента
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 18, 26
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
     * @inheritDoc
     */
    public static function getFfdTags(): array
    {
        return [Ffd105Tags::AGENT_TYPE];
    }
}
