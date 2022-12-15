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
 * Константы, определяющие типы агента
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 18, 26
 */
enum AgentType: string
{
    /**
     * Банковский платёжный агент
     */
    case BANK_PAYING_AGENT = 'bank_paying_agent';

    /**
     * Банковский платёжный субагент
     */
    case BANK_PAYING_SUBAGENT = 'bank_paying_subagent';

    /**
     * Платёжный агент
     */
    case PAYING_AGENT = 'payingAgent';

    /**
     * Платёжный субагент
     */
    case PAYING_SUBAGENT = 'paying_subagent';

    /**
     * Поверенный
     */
    case ATTRONEY = 'attorney';

    /**
     * Комиссионер
     */
    case COMMISSION_AGENT = 'commission_agent';

    /**
     * Другой тип агента
     */
    case ANOTHER = 'another';
}
