<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Entities;

use JsonSerializable;
use Stringable;

/**
 * Абстрактное описание любой сущности, представляемой как json
 */
abstract class Entity implements JsonSerializable, Stringable
{
    /**
     * Возвращает строковое представление json-структуры объекта
     *
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE);
    }
}