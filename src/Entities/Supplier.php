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
 * Класс, описывающий поставшика
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 29
 */
final class Supplier extends Entity
{
    use HasPhones, HasInn;

    /**
     * @var string|null Наименование (1225)
     */
    protected ?string $name = null;

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
