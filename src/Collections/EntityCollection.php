<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types=1);

namespace AtolOnline\Collections;

use AtolOnline\Exceptions\InvalidEntityInCollectionException;
use Illuminate\Support\Collection;

/**
 * Абстрактное описание коллекции любых сущностей
 */
abstract class EntityCollection extends Collection
{
    /**
     * @inheritDoc
     * @throws InvalidEntityInCollectionException
     */
    public function jsonSerialize(): array
    {
        $this->checkCount();
        $this->checkItemsClasses();
        return parent::jsonSerialize();
    }

    /**
     * Проверяет количество элементов коллекции
     *
     * @return static
     */
    public function checkCount(): static
    {
        $this->isEmpty() && throw new (static::EMPTY_EXCEPTION_CLASS)();
        $this->count() > static::MAX_COUNT && throw new (static::TOO_MANY_EXCEPTION_CLASS)(static::MAX_COUNT);
        return $this;
    }

    /**
     * Проверяет корректность класса элемента коллекции
     *
     * @param mixed $item
     * @return static
     * @throws InvalidEntityInCollectionException
     */
    public function checkItemClass(mixed $item): static
    {
        if (!is_object($item) || $item::class !== static::ENTITY_CLASS) {
            throw new InvalidEntityInCollectionException(static::class, static::ENTITY_CLASS, $item);
        }
        return $this;
    }

    /**
     * Проверяет корректность классов элементов коллекции
     *
     * @return static
     * @throws InvalidEntityInCollectionException
     */
    public function checkItemsClasses(): static
    {
        return $this->each(fn ($item) => $this->checkItemClass($item));
    }
}
