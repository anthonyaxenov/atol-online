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

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

/**
 * Абстрактное описание коллекции любых сущностей
 *
 * @todo вот бы ещё проверять классы добавляемых объектов через static.... ммм мякотка
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
     * @throws \Exception
     */
    public function jsonSerialize(): array
    {
        return array_map(function ($value) {
            $this->checkEntityClass($value);
            if ($value instanceof \JsonSerializable) {
                return $value->jsonSerialize();
            } elseif ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true);
            } elseif ($value instanceof Arrayable) {
                return $value->toArray();
            }
            return $value;
        }, $this->all());
    }

    /**
     * Проверяет количество ставок
     *
     * @param array $items Массив элементов, если пустой - проверит содержимое коллекции
     * @return void
     */
    private function checkCount(array $items = []): void
    {
        if (
            count($items) > static::MAX_COUNT ||
            $this->count() === static::MAX_COUNT
        ) {
            $exception = static::EXCEPTION_CLASS;
            throw new $exception(static::MAX_COUNT);
        }
    }

    /**
     * @throws \Exception
     */
    private function checkEntityClass(mixed $item): void
    {
        if (!is_object($item) || $item::class !== static::ENTITY_CLASS) {
            //TODO proper exception
            throw new \Exception(
                'Коллекция должна содержать только объекты класса ' .
                static::ENTITY_CLASS . ', найден ' . $item::class
            );
        }
    }
}
