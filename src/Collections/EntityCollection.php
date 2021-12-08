<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Collections;

use AtolOnline\Exceptions\InvalidEntityInCollectionException;
use Exception;
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
    public function __construct($items = [])
    {
        $this->checkCount($items);
        //TODO следует переделать EntityCollection в обёртку над Collection,
        // ибо ломает методы Collection, которые return new static
        $this->checkItemsClasses($items);
        parent::__construct($items);
    }

    /**
     * @inheritDoc
     */
    public function prepend($value, $key = null): self
    {
        $this->checkCount();
        return parent::prepend($value, $key);
    }

    /**
     * @inheritDoc
     */
    public function add($item): self
    {
        $this->checkCount();
        return parent::add($item);
    }

    /**
     * @inheritDoc
     */
    public function push(...$values): self
    {
        $this->checkCount();
        return parent::push(...$values);
    }

    /**
     * @inheritDoc
     */
    public function merge($items): self
    {
        $this->checkCount();
        return parent::merge($items);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function jsonSerialize(): array
    {
        $this->checkItemsClasses();
        return parent::jsonSerialize();
    }

    /**
     * Проверяет количество элементов коллекции
     *
     * @param array $items Массив элементов, если пустой - проверит содержимое коллекции
     * @return void
     */
    public function checkCount(array $items = []): void
    {
        //TODO проверять пустоту?
        if (count($items) > static::MAX_COUNT || $this->count() === static::MAX_COUNT) {
            throw new (static::EXCEPTION_CLASS)(static::MAX_COUNT);
        }
    }

    /**
     * Проверяет корректность класса элемента коллекции
     *
     * @throws InvalidEntityInCollectionException
     */
    public function checkItemClass(mixed $item): void
    {
        if (!is_object($item) || $item::class !== static::ENTITY_CLASS) {
            throw new InvalidEntityInCollectionException(static::class, static::ENTITY_CLASS, $item);
        }
    }

    /**
     * Проверяет корректность классов элементов коллекции
     *
     * @throws InvalidEntityInCollectionException
     */
    public function checkItemsClasses(array $items = []): void
    {
        (empty($items) ? $this : collect($items))->each(fn ($item) => $this->checkItemClass($item));
    }
}
