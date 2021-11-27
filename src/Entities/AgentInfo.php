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

use AtolOnline\Enums\AgentTypes;
use AtolOnline\Exceptions\InvalidEnumValueException;

/**
 * Класс, описывающий данные агента
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 26-28
 */
class AgentInfo extends Entity
{
    /**
     * @var string|null Признак агента (1057)
     */
    protected ?string $type = null;

    /**
     * @var PayingAgent|null Платёжный агент
     */
    protected ?PayingAgent $paying_agent = null;

    /**
     * @var ReceivePaymentsOperator|null Оператор по приёму платежей
     */
    protected ?ReceivePaymentsOperator $receive_payments_operator = null;

    /**
     * @var MoneyTransferOperator|null Оператор перевода
     */
    protected ?MoneyTransferOperator $money_transfer_operator = null;

    /**
     * Конструктор
     *
     * @param string|null $type Признак агента (1057)
     * @param PayingAgent|null $paying_agent Платёжный агент
     * @param ReceivePaymentsOperator|null $receive_payments_operator Оператор по приёму платежей
     * @param MoneyTransferOperator|null $money_transfer_operator Оператор перевода
     * @throws InvalidEnumValueException
     */
    public function __construct(
        ?string $type = null,
        ?PayingAgent $paying_agent = null,
        ?ReceivePaymentsOperator $receive_payments_operator = null,
        ?MoneyTransferOperator $money_transfer_operator = null,
    ) {
        !is_null($type) && AgentTypes::isValid($type) && $this->setType($type);
        !is_null($paying_agent) && $this->setPayingAgent($paying_agent);
        !is_null($receive_payments_operator) && $this->setReceivePaymentsOperator($receive_payments_operator);
        !is_null($money_transfer_operator) && $this->setMoneyTransferOperator($money_transfer_operator);
    }

    /**
     * Возвращает установленный признак оператора
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Устанавливает признак оператора
     *
     * @param string|null $type
     * @return AgentInfo
     */
    public function setType(?string $type): AgentInfo
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Взвращает установленного платёжного агента
     *
     * @return PayingAgent|null
     */
    public function getPayingAgent(): ?PayingAgent
    {
        return $this->paying_agent;
    }

    /**
     * Устанавливает платёжного агента
     *
     * @param PayingAgent|null $paying_agent
     * @return AgentInfo
     */
    public function setPayingAgent(?PayingAgent $paying_agent): AgentInfo
    {
        $this->paying_agent = $paying_agent;
        return $this;
    }

    /**
     * Возвращает установленного оператора по приёму платежей
     *
     * @return ReceivePaymentsOperator|null
     */
    public function getReceivePaymentsOperator(): ?ReceivePaymentsOperator
    {
        return $this->receive_payments_operator;
    }

    /**
     * Устанавливает оператора по приёму платежей
     *
     * @param ReceivePaymentsOperator|null $receive_payments_operator
     * @return AgentInfo
     */
    public function setReceivePaymentsOperator(?ReceivePaymentsOperator $receive_payments_operator): AgentInfo
    {
        $this->receive_payments_operator = $receive_payments_operator;
        return $this;
    }

    /**
     * Возвращает установленного оператора перевода
     *
     * @return MoneyTransferOperator|null
     */
    public function getMoneyTransferOperator(): ?MoneyTransferOperator
    {
        return $this->money_transfer_operator;
    }

    /**
     * Устанавливает оператора перевода
     *
     * @param MoneyTransferOperator|null $money_transfer_operator
     * @return AgentInfo
     */
    public function setMoneyTransferOperator(?MoneyTransferOperator $money_transfer_operator): AgentInfo
    {
        $this->money_transfer_operator = $money_transfer_operator;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $json = [];
        $this->getType() && $json['type'] = $this->getType();
        $this->getPayingAgent()?->jsonSerialize() && $json['paying_agent'] = $this
            ->getPayingAgent()->jsonSerialize();
        $this->getReceivePaymentsOperator()?->jsonSerialize() && $json['receive_payments_operator'] = $this
            ->getReceivePaymentsOperator()->jsonSerialize();
        $this->getMoneyTransferOperator()?->jsonSerialize() && $json['money_transfer_operator'] = $this
            ->getMoneyTransferOperator()->jsonSerialize();
        return $json;
    }
}
