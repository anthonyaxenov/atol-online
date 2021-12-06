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
use AtolOnline\Entities\Vat;
use AtolOnline\Exceptions\TooManyVatsException;

/**
 * Класс, описывающий коллекцию ставок НДС для документа
 */
final class Vats extends EntityCollection
{
    /**
     * Класс объектов, находящихся в коллекции
     */
    protected const ENTITY_CLASS = Vat::class;

    /**
     * Максмальное количество объектов в коллекции
     */
    protected const MAX_COUNT = Constraints::MAX_COUNT_DOC_VATS;

    /**
     * Класс-наследник TooManyException для выброса при превышении количества
     */
    protected const EXCEPTION_CLASS = TooManyVatsException::class;
}
