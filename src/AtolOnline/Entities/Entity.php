<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use JsonSerializable;

/**
 * Абстрактное описание любой сущности, представляемой как JSON
 *
 * @package AtolOnline\Entities
 */
abstract class Entity implements JsonSerializable
{
    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE);
    }
}