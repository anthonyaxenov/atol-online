<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline;

/**
 * Константы тегов ФФД 1.05
 */
final class Ffd105Tags
{
    /**
     * Телефон или электронный адрес покупателя
     */
    public const CLIENT_PHONE_EMAIL = 1008;

    /**
     * Наименование организации или фамилия, имя, отчество (при наличии), серия и номер паспорта покупателя (клиента)
     */
    public const CLIENT_NAME = 1227;

    /**
     * ИНН организации или покупателя (клиента)
     */
    public const CLIENT_INN = 1228;

    /**
     * Адрес электронной почты отправителя чека
     */
    public const COMPANY_EMAIL = 1117;

    /**
     * ИНН пользователя
     */
    public const COMPANY_INN = 1008;

    /**
     * Применяемая система налогообложения
     */
    public const COMPANY_SNO = 1055;

    /**
     * Место расчётов
     */
    public const COMPANY_PADDRESS = 1187;

    /**
     * Признак агента по предмету расчёта
     */
    public const AGENT_TYPE = 1222;

    /**
     * Телефон оператора по приёму платежей
     */
    public const RPO_PHONES = 1074;

    /**
     * Телефон оператора перевода
     */
    public const MTO_PHONES = 1075;

    /**
     * ИНН оператора перевода
     */
    public const MTO_INN = 1016;

    /**
     * Телефон платёжного агента
     */
    public const PAGENT_PHONE = 1073;

    /**
     * Телефон поставщика
     */
    public const SUPPLIER_PHONES = 1171;

    /**
     * Наименование поставщика
     */
    public const SUPPLIER_NAME = 1225;

    /**
     * ИНН поставщика
     */
    public const SUPPLIER_INN = 1226;

    /**
     * Кассир
     */
    public const CASHIER = 1021;

    /**
     * Наименование предмета расчёта
     */
    public const ITEM_NAME = 1030;

    /**
     * Цена за единицу предмета расчёта с учётом скидок и наценок
     */
    public const ITEM_PRICE = 1079;

    /**
     * Количество предмета расчёта
     */
    public const ITEM_QUANTITY = 1023;

    /**
     * Стоимость предмета расчёта с учётом скидок и наценок
     */
    public const ITEM_SUM = 1043;

    /**
     * Единица измерения предмета расчёта
     */
    public const ITEM_MEASUREMENT_UNIT = 1197;

    /**
     * Код товара
     */
    public const ITEM_NOMENCLATURE_CODE = 1162;

    /**
     * Признак способа расчёта
     */
    public const ITEM_PAYMENT_METHOD = 1214;

    /**
     * Признак предмета расчёта
     */
    public const ITEM_PAYMENT_OBJECT = 1212;

    /**
     * Дополнительный реквизит предмета расчёта
     */
    public const ITEM_USERDATA = 1191;

    /**
     * Сумма акциза с учётом копеек, включённая в стоимость предмета расчёта
     */
    public const ITEM_EXCISE = 1229;

    /**
     * Цифровой код страны происхождения товара в соответствии с Общероссийским классификатором стран мира
     *
     * @see https://ru.wikipedia.org/wiki/Общероссийский_классификатор_стран_мира
     * @see https://classifikators.ru/oksm
     */
    public const ITEM_COUNTRY_CODE = 1230;

    /**
     * Номер таможенной декларации (в соотв. с приказом ФНС России от 24.03.2016 N ММВ-7-15/155)
     */
    public const ITEM_DECLARATION_NUMBER = 1231;

    /**
     * Тип коррекции
     */
    public const CORRECTION_TYPE = 1173;

    /**
     * Дата документа основания для коррекции
     */
    public const CORRECTION_DATE = 1178;

    /**
     * Сумма по чеку (БСО) наличными
     */
    public const PAYMENT_TYPE_CASH = 1031;

    /**
     * Сумма по чеку безналичными
     */
    public const PAYMENT_TYPE_ELECTRON = 1081;

    /**
     * Сумма по чеку предоплатой
     */
    public const PAYMENT_TYPE_PREPAID = 1215;

    /**
     * Сумма по чеку постоплатой
     */
    public const PAYMENT_TYPE_CREDIT = 1216;

    /**
     * Сумма по чеку встречным представлением
     */
    public const PAYMENT_TYPE_OTHER = 1217;

    /**
     * Ставка НДС
     */
    public const ITEM_VAT_TYPE = 1199;

    /**
     * Сумма расчета по чеку без НДС
     */
    public const DOC_VAT_TYPE_NONE = 1105;

    /**
     * Сумма расчета по чеку с НДС по ставке 0%
     */
    public const DOC_VAT_TYPE_VAT0 = 1104;

    /**
     * Сумма НДС чека по ставке 10%
     */
    public const DOC_VAT_TYPE_VAT10 = 1103;

    /**
     * Сумма НДС чека по ставке 20%
     */
    public const DOC_VAT_TYPE_VAT20 = 1102;

    /**
     * Сумма НДС чека по расч. ставке 10/110
     */
    public const DOC_VAT_TYPE_VAT110 = 1107;

    /**
     * Сумма НДС чека по расч. ставке 20/120
     */
    public const DOC_VAT_TYPE_VAT120 = 1106;

    /**
     * Значение дополнительного реквизита чека
     */
    public const DOC_ADD_CHECK_PROP_VALUE = 1192;

    /**
     * Дополнительный реквизит пользователя
     */
    public const DOC_ADD_USER_PROP = 1084;

    /**
     * Наименование дополнительного реквизита пользователя
     */
    public const DOC_ADD_USER_PROP_NAME = 1085;

    /**
     * Значение дополнительного реквизита пользователя
     */
    public const DOC_ADD_USER_PROP_VALUE = 1086;
}
