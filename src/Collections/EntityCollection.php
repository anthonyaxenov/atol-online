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
     */
    public function __construct($items = [])
    {
        $this->checkCount($items);
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
        $this->each(function ($item) {
            $this->checkClass($item);
        });
        return parent::jsonSerialize();
    }

    /**
     * Проверяет количество ставок
     *
     * @param array $items Массив элементов, если пустой - проверит содержимое коллекции
     * @return void
     */
    private function checkCount(array $items = []): void
    {
        if (count($items) > static::MAX_COUNT || $this->count() === static::MAX_COUNT) {
            throw new (static::EXCEPTION_CLASS)(static::MAX_COUNT);
        }
    }

    /**
     * Проверяет корректность класса объекта
     *
     * @throws InvalidEntityInCollectionException
     */
    private function checkClass(mixed $item): void
    {
        if (!is_object($item) || $item::class !== static::ENTITY_CLASS) {
            throw new InvalidEntityInCollectionException(static::class, static::ENTITY_CLASS, $item);
        }
    }
}
