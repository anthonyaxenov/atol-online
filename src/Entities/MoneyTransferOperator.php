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
 * Класс, описывающий оператора перевода
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 28
 */
class MoneyTransferOperator extends Entity
{
    /**
     * @var string|null Наименование (1026)
     */
    protected ?string $name = null;

    /**
     * @var string|null ИНН (1016)
     */
    protected ?string $inn = null;

    /**
     * @var string|null Адрес (1005)
     */
    protected ?string $address = null;

    /**
     * @var Collection Телефоны (1075)
     */
    protected Collection $phones;

    /**
     * Конструктор
     *
     * @param string|null $name Наименование поставщика (1225)
     * @param string|null $inn ИНН (1226)
     * @param string|null $address Адрес (1005)
     * @param array|Collection|null $phones Телефоны поставщика (1171)
     * @throws InvalidInnLengthException
     * @throws InvalidPhoneException
     */
    public function __construct(
        ?string $name = null,
        ?string $inn = null,
        ?string $address = null,
        array|Collection|null $phones = null,
    ) {
        $this->setName($name);
        $this->setInn($inn);
        $this->setAddress($address);
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
     * @return $this
     */
    public function setName(?string $name): self
    {
        // критерии валидной строки не описаны ни в схеме, ни в документации
        $this->name = trim((string)$name) ?: null;
        return $this;
    }

    /**
     * Возвращает установленный адрес места расчётов
     *
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Устанавливает адрес места расчётов
     *
     * @param string|null $address
     * @return $this
     */
    public function setAddress(?string $address): self
    {
        // критерии валидной строки не описаны ни в схеме, ни в документации
        $this->address = trim((string)$address) ?: null;
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
        $this->phones = $phones ?? collect();
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
        $this->inn = $inn ?: null;
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
        $this->getAddress() && $json['address'] = $this->getAddress();
        !$this->getPhones()->isEmpty() && $json['phones'] = $this->getPhones()->toArray();
        return $json;
    }
}
