<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use AtolOnline\Constants\Constraints;
use AtolOnline\Exceptions\TooManyVatsException;

/**
 * Класс, описывающий коллекцию ставок НДС для документа
 */
final class Vats extends EntityCollection
{
    /**
     * Максмальное количество ставок НДС
     */
    protected const MAX_COUNT = Constraints::MAX_COUNT_DOC_VATS;

    /**
     * Класс-наследник TooManyException для выброса при превышении количества
     */
    protected const EXCEPTION_CLASS = TooManyVatsException::class;
}
