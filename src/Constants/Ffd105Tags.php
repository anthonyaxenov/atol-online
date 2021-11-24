<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Constants;

/**
 * Константы тегов ФФД 1.05
 */
final class Ffd105Tags
{
    /**
     * Телефон или электронный адрес покупателя
     */
    const CLIENT_PHONE_EMAIL = 1008;

    /**
     * Наименование организации или фамилия, имя, отчество (при наличии), серия и номер паспорта покупателя (клиента)
     */
    const CLIENT_NAME = 1227;

    /**
     * ИНН организации или покупателя (клиента)
     */
    const CLIENT_INN = 1228;

    /**
     * Адрес электронной почты отправителя чека
     */
    const COMPANY_EMAIL = 1117;

    /**
     * ИНН пользователя
     */
    const COMPANY_INN = 1008;

    /**
     * Место расчетов
     */
    const COMPANY_PADDRESS = 1187;

    /**
     * Телефон оператора по приёму платежей
     */
    const RPO_PHONES = 1074;

    /**
     * Телефон оператора перевода
     */
    const MTO_PHONES = 1075;

    /**
     * ИНН оператора перевода
     */
    const MTO_INN = 1016;

    /**
     * Телефон платёжного агента
     */
    const PAGENT_PHONE = 1073;

    /**
     * Телефон поставщика
     */
    const SUPPLIER_PHONES = 1171;

    /**
     * Наименование поставщика
     */
    const SUPPLIER_NAME = 1225;

    /**
     * ИНН поставщика
     */
    const SUPPLIER_INN = 1226;

    /**
     * Кассир
     */
    const CASHIER = 1021;

    /**
     * Наименование предмета расчета
     */
    const ITEM_NAME = 1030;

    /**
     * Цена за единицу предмета расчета с учетом скидок и наценок
     */
    const ITEM_PRICE = 1079;

    /**
     * Единица измерения предмета расчета
     */
    const ITEM_MEASURE = 1197;

    /**
     * Дополнительный реквизит предмета расчета
     */
    const ITEM_USERDATA = 1191;
}
