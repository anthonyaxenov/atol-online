<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Constants;

/**
 * Класс с константами ограничений
 */
final class Constraints
{
    /**
     * Максимальная длина Callback URL
     */
    const MAX_LENGTH_CALLBACK_URL = 256;

    /**
     * Максимальная длина email
     */
    const MAX_LENGTH_EMAIL = 64;
    
    /**
     * Максимальная длина логина ККТ
     */
    const MAX_LENGTH_LOGIN = 100;
    
    /**
     * Максимальная длина пароля ККТ
     */
    const MAX_LENGTH_PASSWORD = 100;
    
    /**
     * Максимальная длина имени покупателя
     */
    const MAX_LENGTH_CLIENT_NAME = 256;
    
    /**
     * Максимальная длина телефона покупателя
     */
    const MAX_LENGTH_CLIENT_PHONE = 64;
    
    /**
     * Максимальная длина адреса места расчётов
     */
    const MAX_LENGTH_PAYMENT_ADDRESS = 256;
    
    /**
     * Максимальная длина имени кассира
     */
    const MAX_LENGTH_CASHIER_NAME = 64;
    
    /**
     * Максимальная длина наименования предмета расчётов
     */
    const MAX_LENGTH_ITEM_NAME = 128;
    
    /**
     * Максимальная длина единицы измерения предмета расчётов
     */
    const MAX_LENGTH_MEASUREMENT_UNIT = 16;
    
    /**
     * Максимальная длина пользовательских данных для предмета расчётов
     */
    const MAX_LENGTH_USER_DATA = 64;
    
    /**
     * Регулярное выражание для валидации строки ИНН
     */
    const PATTERN_INN = "/(^[0-9]{10}$)|(^[0-9]{12}$)/";
    
    /**
     * Регулярное выражание для валидации строки Callback URL
     */
    const PATTERN_CALLBACK_URL = "/^http(s?)\:\/\/[0-9a-zA-Zа-яА-Я]([-.\w]*[0-9a-zA-Zа-яА-Я])*(:(0-9)*)*(\/?)([a-zAZ0-9а-яА-Я\-\.\?\,\'\/\\\+&=%\$#_]*)?$/";
}