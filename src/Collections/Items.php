<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Collections;

use AtolOnline\Constraints;
use AtolOnline\Entities\Item;
use AtolOnline\Exceptions\EmptyItemsException;
use AtolOnline\Exceptions\TooManyItemsException;

/**
 * Класс, описывающий коллекцию предметов расчёта для документа
 */
final class Items extends EntityCollection
{
    /**
     * Класс объектов, находящихся в коллекции
     */
    protected const ENTITY_CLASS = Item::class;

    /**
     * Максмальное количество объектов в коллекции
     */
    protected const MAX_COUNT = Constraints::MAX_COUNT_DOC_ITEMS;

    /**
     * Класс исключения для выброса при пустой коллекции
     */
    protected const EMPTY_EXCEPTION_CLASS = EmptyItemsException::class;

    /**
     * Класс-наследник TooManyException для выброса при превышении количества
     */
    protected const TOO_MANY_EXCEPTION_CLASS = TooManyItemsException::class;
}
