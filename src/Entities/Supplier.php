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

use AtolOnline\Constants\Constraints;
use AtolOnline\Exceptions\InvalidPhoneException;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий поставшика
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 20-21
 */
class Supplier extends Entity
{
    /**
     * @var Collection Телефоны платёжного агента (1073)
     */
    protected Collection $phones;

    /**
     * Конструктор
     *
     * @param array|Collection|null $phones Телефон оператора по приёму платежей (1074)
     * @throws InvalidPhoneException
     */
    public function __construct(
        array|Collection|null $phones = null,
    ) {
        $this->setPhones($phones);
    }

    /**
     * Возвращает установленные номера телефонов
     *
     * @todo вытащить в трейт
     * @return Collection
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    /**
     * Устанавливает массив номеров телефонов
     *
     * @todo вытащить в трейт
     * @param array|Collection|null $phones
     * @return $this
     * @throws InvalidPhoneException
     */
    public function setPhones(array|Collection|null $phones): self
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
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $json = [];
        !$this->getPhones()->isEmpty() && $json['phones'] = $this->getPhones()->toArray();
        return $json;
    }
}
