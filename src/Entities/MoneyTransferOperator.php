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

use AtolOnline\Exceptions\{
    InvalidInnLengthException,
    InvalidPhoneException
};
use AtolOnline\Traits\{
    HasInn,
    HasPhones
};
use Illuminate\Support\Collection;

/**
 * Класс, описывающий оператора перевода
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 28
 */
class MoneyTransferOperator extends Entity
{
    use HasInn, HasPhones;

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
