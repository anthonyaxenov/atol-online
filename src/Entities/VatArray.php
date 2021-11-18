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

use AtolOnline\Exceptions\TooManyVatsException;

/**
 * Класс, описывающий массив ставок НДС
 *
 * @package AtolOnline\Entities
 */
class VatArray extends Entity
{
    /**
     * Максимальное количество элементов массива
     */
    public const MAX_COUNT = 6;
    
    /**
     * @var Vat[] Массив ставок НДС
     */
    private array $vats = [];

    /**
     * VatArray constructor.
     *
     * @param Vat[]|null $vats Массив ставок НДС
     * @throws TooManyVatsException Слишком много ставок НДС
     */
    public function __construct(?array $vats = null)
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
     * @throws TooManyVatsException Слишком много ставок НДС
     */
    public function set(array $vats): VatArray
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
     * @throws TooManyVatsException Слишком много ставок НДС
     */
    public function add(Vat $vat): VatArray
    {
        if ($this->validateCount()) {
            if (isset($this->vats[$vat->getType()])) {
                $this->vats[$vat->getType()]->addSum($vat->getSum());
            } else {
                $this->vats[$vat->getType()] = $vat;
            }
        }
        return $this;
    }
    
    /**
     * Возвращает массив ставок НДС
     *
     * @return Vat[]
     */
    public function get(): array
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
     * Проверяет количество налоговых ставок
     *
     * @param Vat[]|null $vats Если передать массив, то проверит количество его элементов.
     *                         Иначе проверит количество уже присвоенных элементов.
     * @return bool true если всё хорошо, иначе выбрасывает исключение
     * @throws TooManyVatsException Слишком много ставок НДС
     */
    protected function validateCount(?array $vats = null): bool
    {
        if ((!empty($vats) && count($vats) >= self::MAX_COUNT) || count($this->vats) >= self::MAX_COUNT) {
            throw new TooManyVatsException(count($vats), self::MAX_COUNT);
        }
        return true;
    }
}