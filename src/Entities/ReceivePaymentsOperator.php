<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types=1);

namespace AtolOnline\Entities;

use AtolOnline\Exceptions\InvalidPhoneException;
use AtolOnline\Traits\HasPhones;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий оператора по приёму платежей
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 19-20
 */
final class ReceivePaymentsOperator extends Entity
{
    use HasPhones;

    /**
     * Конструктор
     *
     * @param array|Collection|null $phones Телефоны оператора по приёму платежей (1074)
     * @throws InvalidPhoneException
     */
    public function __construct(array | Collection | null $phones = null)
    {
        $this->setPhones($phones);
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
