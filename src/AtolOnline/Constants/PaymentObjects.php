<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Constants;

/**
 * Константы, определяющие признаки предметов расчёта. Тег ФФД - 1212.
 *
 * @package AtolOnline\Constants
 */
class PaymentObjects
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
}
