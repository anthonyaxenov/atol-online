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
 * Константы, определяющие признаки предметов расчёта
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 23
 */
enum PaymentObject: string
{
    /**
     * Товар, кроме подакцизного
     */
    case COMMODITY = 'commodity';

    /**
     * Товар подакцизный
     */
    case EXCISE = 'excise';

    /**
     * Работа
     */
    case JOB = 'job';

    /**
     * Услуга
     */
    case SERVICE = 'service';

    /**
     * Ставка азартной игры
     */
    case GAMBLING_BET = 'gambling_bet';

    /**
     * Выигрыш азартной игры
     */
    case GAMBLING_PRIZE = 'gambling_prize';

    /**
     * Лотерея
     */
    case LOTTERY = 'lottery';

    /**
     * Выигрыш лотереи
     */
    case LOTTERY_PRIZE = 'lottery_prize';

    /**
     * Предоставление результатов интеллектуальной деятельности
     */
    case INTELLECTUAL_ACTIVITY = 'intellectual_activity';

    /**
     * Платёж (задаток, кредит, аванс, предоплата, пеня, штраф, бонус и пр.)
     */
    case PAYMENT = 'payment';

    /**
     * Агентское вознаграждение
     */
    case AGENT_COMMISSION = 'agent_commission';

    /**
     * Составной предмет расчёта
     *
     * @deprecated Более не используется согласно ФФД 1.05
     * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 25 (payment_object)
     */
    case COMPOSITE = 'composite';

    /**
     * Другой предмет расчёта
     */
    case ANOTHER = 'another';

    /**
     * Имущественное право
     */
    case PROPERTY_RIGHT = 'property_right';

    /**
     * Внереализационный доход
     */
    case NON_OPERATING_GAIN = 'non-operating_gain';

    /**
     * Страховые взносы
     */
    case INSURANCE_PREMIUM = 'insurance_premium';

    /**
     * Торговый сбор
     */
    case SALES_TAX = 'sales_tax';

    /**
     * Курортный сбор
     */
    case RESORT_FEE = 'resort_fee';

    /**
     * Взнос в счёт оплаты пени, штрафе, вознаграждении, бонусе и ином аналогичном предмете расчёта
     */
    case AWARD = 'award';

    /**
     * Залог
     */
    case DEPOSIT = 'deposit';

    /**
     * Расход, уменьшающий доход (в соответствии со статьей 346.16 НК РФ)
     */
    case EXPENSE = 'expense';

    /**
     * Взнос на ОПС ИП
     */
    case PEN_INSURANCE_IP = 'pension_insurance_ip';

    /**
     * Взнос на ОПС
     */
    case PEN_INSURANCE = 'pension_insurance';

    /**
     * Взнос на ОМС ИП
     */
    case MED_INSURANCE_IP = 'medical_insurance_ip';

    /**
     * Взнос на ОМС
     */
    case MED_INSURANCE = 'medical_insurance';

    /**
     * Взнос на ОСС
     */
    case SOC_INSURANCE = 'social_insurance';

    /**
     * Платёж казино
     */
    case CASINO_PAYMENT = 'casino_payment';
}
