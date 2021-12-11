<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use AtolOnline\Collections\{Payments, Vats};
use AtolOnline\Constants\Constraints;
use AtolOnline\Exceptions\{InvalidEntityInCollectionException, TooLongCashierException};
use Exception;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс, описывающий документ коррекции
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 35
 */
class Correction extends Entity
{
    /**
     * @var Company Продавец
     */
    protected Company $company;

    /**
     * @var string|null ФИО кассира
     * @todo вынести в трейт
     */
    protected ?string $cashier = null;

    /**
     * @var CorrectionInfo Данные коррекции
     */
    protected CorrectionInfo $correction_info;

    /**
     * @var Payments Коллекция оплат
     */
    protected Payments $payments;

    /**
     * @var Vats Коллекция ставок НДС
     */
    protected Vats $vats;

    /**
     * Конструктор
     *
     * @param Company $company
     * @param CorrectionInfo $correction_info
     * @param Payments $payments
     * @param Vats $vats
     * @throws InvalidEntityInCollectionException
     * @throws Exception
     */
    public function __construct(
        Company $company,
        CorrectionInfo $correction_info,
        Payments $payments,
        Vats $vats,
    ) {
        $this->setCompany($company)->setCorrectionInfo($correction_info)->setPayments($payments)->setVats($vats);
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
     * Возвращает установленные данные коррекции
     *
     * @return CorrectionInfo
     */
    public function getCorrectionInfo(): CorrectionInfo
    {
        return $this->correction_info;
    }

    /**
     * Устанавливает данные коррекции
     *
     * @param CorrectionInfo $correction_info
     * @return Correction
     */
    public function setCorrectionInfo(CorrectionInfo $correction_info): Correction
    {
        $this->correction_info = $correction_info;
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
        return $this;
    }

    /**
     * @inheritDoc
     * @throws InvalidEntityInCollectionException
     */
    #[ArrayShape([
        'company' => "\AtolOnline\Entities\Company",
        'correction_info' => "\AtolOnline\Entities\CorrectionInfo",
        'payments' => "array",
        'vats' => "\AtolOnline\Collections\Vats|null",
        'cashier' => "null|string"
    ])]
    public function jsonSerialize(): array
    {
        $json = [
            'company' => $this->getCompany(),
            'correction_info' => $this->getCorrectionInfo(),
            'payments' => $this->getPayments()->jsonSerialize(),
            'vats' => $this->getVats(),
        ];
        !is_null($this->getCashier()) && $json['cashier'] = $this->getCashier();
        return $json;
    }
}
