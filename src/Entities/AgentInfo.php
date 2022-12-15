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

use AtolOnline\Enums\AgentType;

/**
 * Класс, описывающий данные агента
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 26-28
 */
final class AgentInfo extends Entity
{
    /**
     * Конструктор
     *
     * @param AgentType|null $type Признак агента (1057)
     * @param PayingAgent|null $payingAgent Платёжный агент
     * @param ReceivePaymentsOperator|null $receivePaymentsOperator Оператор по приёму платежей
     * @param MoneyTransferOperator|null $moneyTransferOperator Оператор перевода
     */
    public function __construct(
        protected ?AgentType $type = null,
        protected ?PayingAgent $payingAgent = null,
        protected ?ReceivePaymentsOperator $receivePaymentsOperator = null,
        protected ?MoneyTransferOperator $moneyTransferOperator = null,
    ) {
        $this->setType($type);
    }

    /**
     * Возвращает установленный признак оператора
     *
     * @return AgentType|null
     */
    public function getType(): ?AgentType
    {
        return $this->type;
    }

    /**
     * Устанавливает признак оператора
     *
     * @param AgentType|null $type
     * @return AgentInfo
     */
    public function setType(?AgentType $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Возвращает установленного платёжного агента
     *
     * @return PayingAgent|null
     */
    public function getPayingAgent(): ?PayingAgent
    {
        return $this->payingAgent;
    }

    /**
     * Устанавливает платёжного агента
     *
     * @param PayingAgent|null $agent
     * @return AgentInfo
     */
    public function setPayingAgent(?PayingAgent $agent): self
    {
        $this->payingAgent = $agent;
        return $this;
    }

    /**
     * Возвращает установленного оператора по приёму платежей
     *
     * @return ReceivePaymentsOperator|null
     */
    public function getReceivePaymentsOperator(): ?ReceivePaymentsOperator
    {
        return $this->receivePaymentsOperator;
    }

    /**
     * Устанавливает оператора по приёму платежей
     *
     * @param ReceivePaymentsOperator|null $operator
     * @return AgentInfo
     */
    public function setReceivePaymentsOperator(?ReceivePaymentsOperator $operator): self
    {
        $this->receivePaymentsOperator = $operator;
        return $this;
    }

    /**
     * Возвращает установленного оператора перевода
     *
     * @return MoneyTransferOperator|null
     */
    public function getMoneyTransferOperator(): ?MoneyTransferOperator
    {
        return $this->moneyTransferOperator;
    }

    /**
     * Устанавливает оператора перевода
     *
     * @param MoneyTransferOperator|null $operator
     * @return AgentInfo
     */
    public function setMoneyTransferOperator(?MoneyTransferOperator $operator): self
    {
        $this->moneyTransferOperator = $operator;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $json = [];
        if ($this?->type) {
            $json['type'] = $this->getType();
        }
        if ($this->payingAgent?->jsonSerialize()) {
            $json['paying_agent'] = $this->payingAgent->jsonSerialize();
        }
        if ($this->receivePaymentsOperator?->jsonSerialize()) {
            $json['receive_payments_operator'] = $this->receivePaymentsOperator->jsonSerialize();
        }
        if ($this->moneyTransferOperator?->jsonSerialize()) {
            $json['money_transfer_operator'] = $this->moneyTransferOperator->jsonSerialize();
        }
        return $json;
    }
}
