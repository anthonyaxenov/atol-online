<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use AtolOnline\Exceptions\AtolTooManyVatsException;

/**
 * Класс, описывающий массив ставок НДС
 *
 * @package AtolOnline\Entities
 */
class VatArray extends AtolEntity
{
    /**
     * Максимальное количество элементов в массиве
     */
    public const MAX_COUNT = 6;
    
    /**
     * @var Vat[] Массив ставок НДС
     */
    private $vats = [];
    
    /**
     * VatArray constructor.
     *
     * @param Vat[]|null $vats Массив ставок НДС
     * @throws AtolTooManyVatsException Слишком много ставок НДС
     */
    public function __construct(array $vats = null)
    {
        if ($vats) {
            $this->set($vats);
        }
    }
    
    /**
     * Устанавливает массив ставок НДС
     *
     * @param Vat[] $vats Массив ставок НДС
     * @return $this
     * @throws AtolTooManyVatsException Слишком много ставок НДС
     */
    public function set(array $vats)
    {
        if ($this->validateCount($vats)) {
            $this->vats = $vats;
        }
        return $this;
    }
    
    /**
     * Добавляет новую ставку НДС в массив
     *
     * @param Vat $vat Объект ставки НДС
     * @return $this
     * @throws AtolTooManyVatsException Слишком много ставок НДС
     */
    public function add(Vat $vat)
    {
        if ($this->validateCount()) {
            $this->vats[] = $vat;
        }
        return $this;
    }
    
    /**
     * Возвращает массив ставок НДС
     *
     * @return Vat[]
     */
    public function get()
    {
        return $this->vats;
    }
    
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $result = [];
        foreach ($this->get() as $vat) {
            $result[] = $vat->jsonSerialize();
        }
        return $result;
    }
    
    /**
     * Проверяет количество элементов в массиве
     *
     * @param array|null $vats  Если передать массив, то проверит количество его элементов.
     *                          Иначе проверит количество уже присвоенных элементов.
     * @return bool
     * @throws AtolTooManyVatsException Слишком много ставок НДС
     */
    protected function validateCount(array $vats = null)
    {
        if (($vats && is_array($vats) && count($vats) >= self::MAX_COUNT) || count($this->vats) == self::MAX_COUNT) {
            throw new AtolTooManyVatsException(self::MAX_COUNT);
        }
        return true;
    }
}