<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use AtolOnline\Api\SellSchema;
use AtolOnline\Exceptions\AtolTooFewItemsException;
use AtolOnline\Exceptions\AtolTooManyItemsException;

/**
 * Класс, описывающий массив предметов расчёта
 *
 * @package AtolOnline\Entities
 */
class ItemArray extends AtolEntity
{
    /**
     * Максимальное количество элементов в массиве
     * По документации ограничение по количеству предметов расчёта = от 1 до 100,
     * однако в схеме sell не указан receipt.properties.items.maxItems
     */
    public const MAX_COUNT = 100;
    
    /**
     * @var \AtolOnline\Entities\Item[] Массив предметов расчёта
     */
    private $items = [];
    
    /**
     * ItemArray constructor.
     *
     * @param \AtolOnline\Entities\Item[]|null $items Массив предметов расчёта
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException Слишком много предметов расчёта
     */
    public function __construct(array $items = null)
    {
        if ($items) {
            $this->set($items);
        }
    }
    
    /**
     * Устанавливает массив предметов расчёта
     *
     * @param \AtolOnline\Entities\Item[] $items Массив предметов расчёта
     * @return $this
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException Слишком много предметов расчёта
     */
    public function set(array $items)
    {
        if ($this->validateCount($items)) {
            $this->items = $items;
        }
        return $this;
    }
    
    /**
     * Добавляет предмет расчёта в массив
     *
     * @param \AtolOnline\Entities\Item $item Объект предмета расчёта
     * @return $this
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException Слишком много предметов расчёта
     */
    public function add(Item $item)
    {
        if ($this->validateCount()) {
            $this->items[] = $item;
        }
        return $this;
    }
    
    /**
     * Возвращает массив предметов расчёта
     *
     * @return \AtolOnline\Entities\Item[]
     */
    public function get()
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
     * @param array|null $items Если передать массив, то проверит количество его элементов.
     *                          Иначе проверит количество уже присвоенных элементов.
     * @return bool true если всё хорошо, иначе выбрасывает исключение
     * @throws \AtolOnline\Exceptions\AtolTooFewItemsException  Слишком мало предметов расчёта
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException Слишком много предметов расчёта
     */
    protected function validateCount(array $items = null)
    {
        return empty($items)
            ? $this->checkCount($this->items)
            : $this->checkCount($items);
    }
    
    /**
     * Проверяет количество элементов в указанном массиве
     *
     * @param array|null $items
     * @return bool true если всё хорошо, иначе выбрасывает исключение
     * @throws \AtolOnline\Exceptions\AtolTooFewItemsException  Слишком мало предметов расчёта
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException Слишком много предметов расчёта
     */
    protected function checkCount(?array $items = null)
    {
        $min_count = SellSchema::get()->receipt->properties->items->minItems;
        $max_count = self::MAX_COUNT; // maxItems отстутствует в схеме sell
        if (empty($items) || count($items) < $min_count) {
            throw new AtolTooFewItemsException($min_count);
        } elseif (count($items) >= $max_count) {
            throw new AtolTooManyItemsException($max_count);
        } else {
            return true;
        }
    }
}