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
use AtolOnline\Exceptions\TooLongPayingAgentOperationException;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий данные платёжного агента
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 19
 */
class PayingAgent extends Entity
{
    /**
     * @var string|null Наименование операции (1044)
     */
    protected ?string $operation = null;

    /**
     * @var Collection Телефоны платежного агента (1073)
     */
    protected Collection $phones;

    /**
     * Конструктор
     *
     * @param string|null $operation Наименование операции (1044)
     * @param array|Collection|null $phones Телефоны платежного агента (1073)
     * @throws TooLongPayingAgentOperationException
     * @throws InvalidPhoneException
     */
    public function __construct(
        ?string $operation = null,
        array|Collection|null $phones = null,
    ) {
        !is_null($operation) && $this->setOperation($operation);
        $this->setPhones($phones);
    }

    /**
     * Устанавливает операцию
     *
     * @param string|null $operation
     * @return $this
     * @throws TooLongPayingAgentOperationException
     */
    public function setOperation(?string $operation): self
    {
        if (!is_null($operation)) {
            $operation = trim($operation);
            if (mb_strlen($operation) > Constraints::MAX_LENGTH_PAYING_AGENT_OPERATION) {
                throw new TooLongPayingAgentOperationException($operation);
            }
        }
        $this->operation = empty($operation) ? null : $operation;
        return $this;
    }

    /**
     * Вoзвращает установленную операцию
     *
     * @return string|null
     */
    public function getOperation(): ?string
    {
        return $this->operation;
    }

    /**
     * Устанавливает массив номеров телефонов
     *
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
     * Возвращает установленные
     *
     * @return Collection
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $json = [];
        $this->getOperation() && $json['operation'] = $this->getOperation();
        !$this->getPhones()->isEmpty() && $json['phones'] = $this->getPhones()->toArray();
        return $json;
    }
}
