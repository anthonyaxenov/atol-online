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
use AtolOnline\Exceptions\InvalidInnLengthException;
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
     * @var string|null Наименование (1225)
     */
    protected ?string $name = null;

    /**
     * @var string|null ИНН (1226)
     */
    protected ?string $inn = null;

    /**
     * @var Collection Телефоны (1171)
     */
    protected Collection $phones;

    /**
     * Конструктор
     *
     * @param string|null $name Наименование поставщика (1225)
     * @param string|null $inn ИНН (1226)
     * @param array|Collection|null $phones Телефоны поставщика (1171)
     * @throws InvalidInnLengthException
     * @throws InvalidPhoneException
     */
    public function __construct(
        ?string $name = null,
        ?string $inn = null,
        array|Collection|null $phones = null,
    ) {
        !is_null($name) && $this->setName($name);
        !is_null($inn) && $this->setInn($inn);
        $this->setPhones($phones);
    }

    /**
     * Возвращает установленное наименование поставщика
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Устанавливает наименование поставщика
     *
     * @param string|null $name
     * @return Supplier
     */
    public function setName(?string $name): self
    {
        // критерии к длине строки не описаны ни в схеме, ни в документации
        $this->name = trim($name) ?: null;
        return $this;
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
     * Возвращает установленный ИНН
     *
     * @return string|null
     */
    public function getInn(): ?string
    {
        return $this->inn;
    }

    /**
     * Устанавливает ИНН
     *
     * @param string|null $inn
     * @return $this
     * @throws InvalidInnLengthException Некорректная длина ИНН
     */
    public function setInn(?string $inn): self
    {
        if (is_string($inn)) {
            $inn = preg_replace('/[^\d]/', '', trim($inn));
            if (preg_match_all(Constraints::PATTERN_INN, $inn) === 0) {
                throw new InvalidInnLengthException($inn);
            }
        }
        $this->inn = empty($inn) ? null : $inn;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $json = [];
        $this->getName() && $json['name'] = $this->getName();
        $this->getInn() && $json['inn'] = $this->getInn();
        !$this->getPhones()->isEmpty() && $json['phones'] = $this->getPhones()->toArray();
        return $json;
    }
}
