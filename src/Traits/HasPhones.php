<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Traits;

use AtolOnline\Constraints;
use AtolOnline\Exceptions\InvalidPhoneException;
use Illuminate\Support\Collection;

/**
 * Трейт для сущностей, которые могут иметь массив номеров телефонов
 */
trait HasPhones
{
    /**
     * @var Collection Телефоны платёжного агента (1073), поставщика (1171), оператора по приёму платежей (1074)
     */
    protected Collection $phones;

    /**
     * Устанавливает массив номеров телефонов
     *
     * @param array|Collection|null $phones
     * @return $this
     * @throws InvalidPhoneException
     */
    public function setPhones(array | Collection | null $phones): static
    {
        if (!is_null($phones)) {
            $phones = is_array($phones) ? collect($phones) : $phones;
            $phones->each(function ($phone) {
                $phone = preg_replace('/[^\d]/', '', trim($phone));
                if (preg_match(Constraints::PATTERN_PHONE, $phone) != 1) {
                    throw new InvalidPhoneException($phone);
                }
            });
        }
        $this->phones = empty($phones) ? collect() : $phones;
        return $this;
    }

    /**
     * Возвращает установленные номера телефонов
     *
     * @return Collection
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }
}
