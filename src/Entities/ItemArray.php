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

use AtolOnline\Exceptions\TooManyItemsException;

/**
 * Класс, описывающий массив предметов расчёта
 *
 * @package AtolOnline\Entities
 */
class ItemArray extends Entity
{
    /**
     * Максимальное количество элементов в массиве
     * По документации ограничение по количеству предметов расчёта = от 1 до 100,
     * однако в схеме sell не указан receipt.properties.items.maxItems
     */
    public const MAX_COUNT = 100;
    
    /**
     * @var Item[] Массив предметов расчёта
     */
    private array $items = [];

    /**
     * ItemArray constructor.
     *
     * @param Item[]|null $items Массив предметов расчёта
     * @throws TooManyItemsException Слишком много предметов расчёта
     */
    public function __construct(?array $items = null)
    {
        if ($items) {
            $this->set($items);
        }
    }

    /**
     * Устанавливает массив предметов расчёта
     *
     * @param Item[] $items Массив предметов расчёта
     * @return $this
     * @throws TooManyItemsException Слишком много предметов расчёта
     */
    public function set(array $items): ItemArray
    {
        if ($this->validateCount($items)) {
            $this->items = $items;
        }
        return $this;
    }

    /**
     * Добавляет предмет расчёта в массив
     *
     * @param Item $item Объект предмета расчёта
     * @return $this
     * @throws TooManyItemsException Слишком много предметов расчёта
     */
    public function add(Item $item): ItemArray
    {
        if ($this->validateCount()) {
            $this->items[] = $item;
        }
        return $this;
    }
    
    /**
     * Возвращает массив предметов расчёта
     *
     * @return Item[]
     */
    public function get(): array
    {
        return $this->items;
    }
    
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $result = [];
        foreach ($this->get() as $item) {
            $result[] = $item->jsonSerialize();
        }
        return $result;
    }

    /**
     * Проверяет количество предметов расчёта
     *
     * @param Item[]|null $items Если передать массив, то проверит количество его элементов.
     *                           Иначе проверит количество уже присвоенных элементов.
     * @return bool true если всё хорошо, иначе выбрасывает исключение
     * @throws TooManyItemsException Слишком много предметов расчёта
     */
    protected function validateCount(?array $items = null): bool
    {
        if ((!empty($items) && count($items) >= self::MAX_COUNT) || count($this->items) >= self::MAX_COUNT) {
            throw new TooManyItemsException(count($items), self::MAX_COUNT);
        }
        return true;
    }
}