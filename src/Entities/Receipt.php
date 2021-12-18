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

use AtolOnline\Api\KktFiscalizer;
use AtolOnline\Api\KktResponse;
use AtolOnline\Collections\Items;
use AtolOnline\Collections\Payments;
use AtolOnline\Collections\Vats;
use AtolOnline\Constants\Constraints;
use AtolOnline\Exceptions\AuthFailedException;
use AtolOnline\Exceptions\EmptyItemsException;
use AtolOnline\Exceptions\EmptyLoginException;
use AtolOnline\Exceptions\EmptyPasswordException;
use AtolOnline\Exceptions\InvalidEntityInCollectionException;
use AtolOnline\Exceptions\InvalidInnLengthException;
use AtolOnline\Exceptions\InvalidPaymentAddressException;
use AtolOnline\Exceptions\TooLongAddCheckPropException;
use AtolOnline\Exceptions\TooLongCashierException;
use AtolOnline\Exceptions\TooLongPaymentAddressException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Класс, описывающий документ прихода, расхода, возврата прихода, возврата расхода
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 17
 */
final class Receipt extends Entity
{
    /**
     * Тип документа
     */
    public const DOC_TYPE = 'receipt';

    /**
     * @var Client Покупатель
     */
    protected Client $client;

    /**
     * @todo вынести в трейт?
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
     * @todo вынести в трейт?
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
     * @todo вынести в трейт?
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @param Items $items
     * @return $this
     * @throws InvalidEntityInCollectionException
     * @throws Exception
     * @throws EmptyItemsException
     */
    public function setItems(Items $items): self
    {
        $items->checkCount();
        $items->checkItemsClasses();
        $this->items = $items;
        $this->getItems()->each(fn($item) => $this->total += $item->getSum());
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
     * @return $this
     * @throws InvalidEntityInCollectionException
     */
    public function setPayments(Payments $payments): self
    {
        $payments->checkCount();
        $payments->checkItemsClasses();
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
     * @return $this
     * @throws Exception
     */
    public function setVats(?Vats $vats): self
    {
        $vats->checkCount();
        $vats->checkItemsClasses();
        $this->vats = $vats;
        /** @var Vat $vat */
        $this->getVats()->each(fn($vat) => $vat->setSum($this->getTotal()));
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
     * @return $this
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
     * @return $this
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
        $this->add_check_props = $add_check_props ?: null;
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
     * @return $this
     */
    public function setAddUserProps(?AdditionalUserProps $add_user_props): self
    {
        $this->add_user_props = $add_user_props;
        return $this;
    }

    /**
     * Регистрирует приход по текущему документу
     *
     * @param KktFiscalizer $fiscalizer Объект фискализатора
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан новый UUID)
     * @return KktResponse|null
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws TooLongPaymentAddressException
     */
    public function sell(KktFiscalizer $fiscalizer, ?string $external_id = null): ?KktResponse
    {
        return $fiscalizer->sell($this, $external_id);
    }

    /**
     * Регистрирует возврат прихода по текущему документу
     *
     * @param KktFiscalizer $fiscalizer Объект фискализатора
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан новый UUID)
     * @return KktResponse|null
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws TooLongPaymentAddressException
     */
    public function sellRefund(KktFiscalizer $fiscalizer, ?string $external_id = null): ?KktResponse
    {
        return $fiscalizer->sellRefund($this, $external_id);
    }

    /**
     * Регистрирует расход по текущему документу
     *
     * @param KktFiscalizer $fiscalizer Объект фискализатора
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан новый UUID)
     * @return KktResponse|null
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws TooLongPaymentAddressException
     */
    public function buy(KktFiscalizer $fiscalizer, ?string $external_id = null): ?KktResponse
    {
        return $fiscalizer->buy($this, $external_id);
    }

    /**
     * Регистрирует возврат расхода по текущему документу
     *
     * @param KktFiscalizer $fiscalizer Объект фискализатора
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан новый UUID)
     * @return KktResponse|null
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws TooLongPaymentAddressException
     */
    public function buyRefund(KktFiscalizer $fiscalizer, ?string $external_id = null): ?KktResponse
    {
        return $fiscalizer->buyRefund($this, $external_id);
    }

    /**
     * Возвращает массив для кодирования в json
     *
     * @throws Exception
     */
    public function jsonSerialize(): array
    {
        $json = [
            'client' => $this->getClient()->jsonSerialize(),
            'company' => $this->getCompany()->jsonSerialize(),
            'items' => $this->getItems()->jsonSerialize(),
            'total' => $this->getTotal(),
            'payments' => $this->getPayments()->jsonSerialize(),
        ];
        $this->getAgentInfo()?->jsonSerialize() && $json['agent_info'] = $this->getAgentInfo()->jsonSerialize();
        $this->getSupplier()?->jsonSerialize() && $json['supplier_info'] = $this->getSupplier()->jsonSerialize();
        $this->getVats()?->isNotEmpty() && $json['vats'] = $this->getVats();
        !is_null($this->getAddCheckProps()) && $json['additional_check_props'] = $this->getAddCheckProps();
        !is_null($this->getCashier()) && $json['cashier'] = $this->getCashier();
        $this->getAddUserProps()?->jsonSerialize() &&
        $json['additional_user_props'] = $this->getAddUserProps()->jsonSerialize();
        return $json;
    }
}
