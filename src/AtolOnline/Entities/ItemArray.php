<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

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
     */
    const MAX_COUNT = 100;
    
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
     * Проверяет количество элементов в массиве
     *
     * @param array|null $items Если передать массив, то проверит количество его элементов.
     *                          Иначе проверит количество уже присвоенных элементов.
     * @return bool
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException Слишком много предметов расчёта
     */
    protected function validateCount(array $items = null)
    {
        if (($items && is_array($items) && count($items) >= self::MAX_COUNT) || count($this->items) == self::MAX_COUNT) {
            throw new AtolTooManyItemsException(self::MAX_COUNT);
        }
        return true;
    }
}