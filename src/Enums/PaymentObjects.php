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
 * Константы, определяющие признаки предметов расчёта
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 23
 */
final class PaymentObjects extends Enum
{
    /**
     * Товар, кроме подакцизного
     */
    const COMMODITY = 'commodity';

    /**
     * Товар подакцизный
     */
    const EXCISE = 'excise';

    /**
     * Работа
     */
    const JOB = 'job';

    /**
     * Услуга
     */
    const SERVICE = 'service';

    /**
     * Ставка азартной игры
     */
    const GAMBLING_BET = 'gambling_bet';

    /**
     * Выигрыш азартной игры
     */
    const GAMBLING_PRIZE = 'gambling_prize';

    /**
     * Лотерея
     */
    const LOTTERY = 'lottery';

    /**
     * Выигрыш лотереи
     */
    const LOTTERY_PRIZE = 'lottery_prize';

    /**
     * Предоставление результатов интеллектуальной деятельности
     */
    const INTELLECTUAL_ACTIVITY = 'intellectual_activity';

    /**
     * Платёж (задаток, кредит, аванс, предоплата, пеня, штраф, бонус и пр.)
     */
    const PAYMENT = 'payment';

    /**
     * Агентское вознаграждение
     */
    const AGENT_COMMISSION = 'agent_commission';

    /**
     * Составной предмет расчёта
     * @deprecated Более не используется согласно ФФД 1.05
     * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 25 (payment_object)
     */
    const COMPOSITE = 'composite';

    /**
     * Другой предмет расчёта
     */
    const ANOTHER = 'another';

    /**
     * Имущественное право
     */
    const PROPERTY_RIGHT = 'property_right';

    /**
     * Внереализационный доход
     */
    const NON_OPERATING_GAIN = 'non-operating_gain';

    /**
     * Страховые взносы
     */
    const INSURANCE_PREMIUM = 'insurance_premium';

    /**
     * Торговый сбор
     */
    const SALES_TAX = 'sales_tax';

    /**
     * Курортный сбор
     */
    const RESORT_FEE = 'resort_fee';

    /**
     * Взнос в счёт оплаты пени, штрафе, вознаграждении, бонусе и ином аналогичном предмете расчёта
     */
    const AWARD = 'award';

    /**
     * Залог
     */
    const DEPOSIT = 'deposit';

    /**
     * Расход, уменьшающий доход (в соответствии со статьей 346.16 НК РФ)
     */
    const EXPENSE = 'expense';

    /**
     * Взнос на ОПС ИП
     */
    const PEN_INSURANCE_IP = 'pension_insurance_ip';

    /**
     * Взнос на ОПС
     */
    const PEN_INSURANCE = 'pension_insurance';

    /**
     * Взнос на ОМС ИП
     */
    const MED_INSURANCE_IP = 'medical_insurance_ip';

    /**
     * Взнос на ОМС
     */
    const MED_INSURANCE = 'medical_insurance';

    /**
     * Взнос на ОСС
     */
    const SOC_INSURANCE = 'social_insurance';

    /**
     * Платёж казино
     */
    const CASINO_PAYMENT = 'casino_payment';

    /**
     * @inheritDoc
     */
    public static function getFfdTags(): array
    {
        return [Ffd105Tags::ITEM_PAYMENT_OBJECT];
    }
}
