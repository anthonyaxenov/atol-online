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
 * Константы, определяющие параметры тестовой среды
 *
 * @see     https://online.atol.ru/files/ffd/test_sreda.txt
 * @package AtolOnline\Constants
 */
class TestEnvParams
{
    /**
     * Логин
     */
    const LOGIN = 'v4-online-atol-ru';
    
    /**
     * Пароль
     */
    const PASSWORD = 'iGFFuihss';
    
    /**
     * Группа
     */
    const GROUP = 'v4-online-atol-ru_4179';
    
    /**
     * Система налогообложения
     */
    const SNO = SnoTypes::OSN;
    
    /**
     * ИНН
     */
    const INN = '5544332219';
    
    /**
     * Адрес места расчётов
     */
    const PAYMENT_ADDRESS = 'https://v4.online.atol.ru';
}