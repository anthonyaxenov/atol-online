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

use AtolOnline\Collections\Items;
use AtolOnline\Collections\Payments;
use AtolOnline\Collections\Vats;
use AtolOnline\Constants\Constraints;
use AtolOnline\Exceptions\EmptyItemsException;
use AtolOnline\Exceptions\EmptyPaymentsException;
use AtolOnline\Exceptions\EmptyVatsException;
use AtolOnline\Exceptions\InvalidEntityInCollectionException;
use AtolOnline\Exceptions\TooLongAddCheckPropException;
use AtolOnline\Exceptions\TooLongCashierException;
use Exception;

/**
 * Класс, описывающий документ прихода, расхода, возврата прихода, возврата расхода
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 17
 */
class Receipt extends Entity
{
    /**
     * @var Client Покупатель
     */
    protected Client $client;

    /**
     * @var Company Продавец
     */
    protected Company $company;

    /**
     * @var AgentInfo|null Агент
     */
    protected ?AgentInfo $agent_info = null;

    /**
     * @var Supplier|null Поставщик
     */
    protected ?Supplier $supplier = null;

    /**
     * @var Items Коллекция предметов расчёта
     */
    protected Items $items;

    /**
     * @var Payments Коллекция оплат
     */
    protected Payments $payments;

    /**
     * @var Vats|null Коллекция ставок НДС
     */
    protected ?Vats $vats = null;

    /**
     * @var float Итоговая сумма чека
     */
    protected float $total = 0;

    /**
     * @var string|null ФИО кассира
     */
    protected ?string $cashier = null;

    /**
     * @var string|null Дополнительный реквизит
     */
    protected ?string $add_check_props = null;

    /**
     * @var AdditionalUserProps|null Дополнительный реквизит пользователя
     */
    protected ?AdditionalUserProps $add_user_props = null;

    /**
     * Конструктор
     *
     * @param Client $client
     * @param Company $company
     * @param Items $items
     * @param Payments $payments
     * @throws EmptyItemsException
     * @throws EmptyPaymentsException
     * @throws InvalidEntityInCollectionException
     */
    public function __construct(Client $client, Company $company, Items $items, Payments $payments)
    {
        $this->setClient($client)->setCompany($company)->setItems($items)->setPayments($payments);
    }

    /**
     * Возвращает установленного покупателя
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Устанаваливает покупателя
     *
     * @param Client $client
     * @return Receipt
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Возвращает установленного продавца
     *
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * Устанаваливает продавца
     *
     * @param Company $company
     * @return Receipt
     */
    public function setCompany(Company $company): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Возвращает установленного агента
     *
     * @return AgentInfo|null
     */
    public function getAgentInfo(): ?AgentInfo
    {
        return $this->agent_info;
    }

    /**
     * Устанаваливает агента
     *
     * @param AgentInfo|null $agent_info
     * @return Receipt
     */
    public function setAgentInfo(?AgentInfo $agent_info): self
    {
        $this->agent_info = $agent_info;
        return $this;
    }

    /**
     * Поставщика
     *
     * @return Supplier|null
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * Поставщика
     *
     * @param Supplier|null $supplier
     * @return Receipt
     */
    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * Возвращает установленную коллекцию предметов расчёта
     *
     * @return Items
     */
    public function getItems(): Items
    {
        return $this->items ?? new Items();
    }

    /**
     * Устанаваливает коллекцию предметов расчёта
     *
     * @todo исключение при пустой коллекции
     * @param Items $items
     * @return Receipt
     * @throws EmptyItemsException
     * @throws InvalidEntityInCollectionException
     * @throws Exception
     */
    public function setItems(Items $items): self
    {
        if ($items->isEmpty()) {
            throw new EmptyItemsException();
        }
        $items->checkItemsClasses();
        $this->items = $items;
        $this->getItems()->each(fn ($item) => $this->total += $item->getSum());
        $this->total = round($this->total, 2);
        return $this;
    }

    /**
     * Возвращает установленную коллекцию оплат
     *
     * @return Payments
     */
    public function getPayments(): Payments
    {
        return $this->payments;
    }

    /**
     * Устанаваливает коллекцию оплат
     *
     * @param Payments $payments
     * @return Receipt
     * @throws EmptyPaymentsException
     */
    public function setPayments(Payments $payments): self
    {
        if ($payments->isEmpty()) {
            throw new EmptyPaymentsException();
        }
        $this->payments = $payments;
        return $this;
    }

    /**
     * Возвращает установленную коллекцию ставок НДС
     *
     * @return Vats|null
     */
    public function getVats(): ?Vats
    {
        return $this->vats ?? new Vats();
    }

    /**
     * Устанаваливает коллекцию ставок НДС
     *
     * @param Vats|null $vats
     * @return Receipt
     * @throws EmptyVatsException
     * @throws Exception
     */
    public function setVats(?Vats $vats): self
    {
        if ($vats->isEmpty()) {
            throw new EmptyVatsException();
        }
        $this->vats = $vats;
        /** @var Vat $vat */
        $this->getVats()->each(fn ($vat) => $vat->setSum($this->getTotal()));
        return $this;
    }

    /**
     * Возвращает полную сумму чека
     *
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * Возвращает установленного кассира
     *
     * @return string|null
     */
    public function getCashier(): ?string
    {
        return $this->cashier;
    }

    /**
     * Устанаваливает кассира
     *
     * @param string|null $cashier
     * @return Receipt
     * @throws TooLongCashierException
     */
    public function setCashier(?string $cashier): self
    {
        if (is_string($cashier)) {
            $cashier = trim($cashier);
            if (mb_strlen($cashier) > Constraints::MAX_LENGTH_CASHIER_NAME) {
                throw new TooLongCashierException($cashier);
            }
        }
        $this->cashier = $cashier ?: null;
        return $this;
    }

    /**
     * Возвращает установленный дополнительный реквизит чека
     *
     * @return string|null
     */
    public function getAddCheckProps(): ?string
    {
        return $this->add_check_props;
    }

    /**
     * Устанаваливает дополнительный реквизит чека
     *
     * @param string|null $add_check_props
     * @return Receipt
     * @throws TooLongAddCheckPropException
     */
    public function setAddCheckProps(?string $add_check_props): self
    {
        if (is_string($add_check_props)) {
            $add_check_props = trim($add_check_props);
            if (mb_strlen($add_check_props) > Constraints::MAX_LENGTH_ADD_CHECK_PROP) {
                throw new TooLongAddCheckPropException($add_check_props);
            }
        }
        $this->add_check_props = empty($add_check_props) ? null : $add_check_props;
        return $this;
    }

    /**
     * Возвращает установленный дополнительный реквизит пользователя
     *
     * @return AdditionalUserProps|null
     */
    public function getAddUserProps(): ?AdditionalUserProps
    {
        return $this->add_user_props;
    }

    /**
     * Устанаваливает дополнительный реквизит пользователя
     *
     * @param AdditionalUserProps|null $add_user_props
     * @return Receipt
     */
    public function setAddUserProps(?AdditionalUserProps $add_user_props): self
    {
        $this->add_user_props = $add_user_props;
        return $this;
    }

    /**
     * Возвращает массив для кодирования в json
     *
     * @throws Exception
     */
    public function jsonSerialize(): array
    {
        $json = [
            'client' => $this->getClient(),
            'company' => $this->getCompany(),
            'items' => $this->getItems(),
            'total' => $this->getTotal(),
            'payments' => $this->getPayments(),
        ];
        $this->getAgentInfo()?->jsonSerialize() && $json['agent_info'] = $this->getAgentInfo();
        $this->getSupplier()?->jsonSerialize() && $json['supplier_info'] = $this->getSupplier();
        $this->getVats()?->jsonSerialize() && $json['vats'] = $this->getVats();
        !is_null($this->getAddCheckProps()) && $json['additional_check_props'] = $this->getAddCheckProps();
        !is_null($this->getCashier()) && $json['cashier'] = $this->getCashier();
        $this->getAddUserProps()?->jsonSerialize() && $json['additional_user_props'] = $this->getAddUserProps();
        return $json;
    }
}
