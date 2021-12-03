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
use AtolOnline\Exceptions\{
    InvalidPhoneException,
    TooLongPayingAgentOperationException
};
use AtolOnline\Traits\HasPhones;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий платёжного агента
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 19
 */
final class PayingAgent extends Entity
{
    use HasPhones;

    /**
     * @var string|null Наименование операции (1044)
     */
    protected ?string $operation = null;

    /**
     * Конструктор
     *
     * @param string|null $operation Наименование операции (1044)
     * @param array|Collection|null $phones Телефоны платёжного агента (1073)
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
