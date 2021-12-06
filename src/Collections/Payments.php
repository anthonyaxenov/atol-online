<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Collections;

use AtolOnline\Constants\Constraints;
use AtolOnline\Entities\Payment;
use AtolOnline\Exceptions\TooManyPaymentsException;

/**
 * Класс, описывающий коллекцию оплат для документа
 */
final class Payments extends EntityCollection
{
    /**
     * Класс объектов, находящихся в коллекции
     */
    protected const ENTITY_CLASS = Payment::class;

    /**
     * Максмальное количество объектов в коллекции
     */
    protected const MAX_COUNT = Constraints::MAX_COUNT_DOC_PAYMENTS;

    /**
     * Класс-наследник TooManyException для выброса при превышении количества
     */
    protected const EXCEPTION_CLASS = TooManyPaymentsException::class;
}
