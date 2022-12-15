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

use AtolOnline\{
    Constraints,
    Enums\SnoType,
    Traits\HasEmail,
    Traits\HasInn};
use AtolOnline\Exceptions\{
    InvalidEmailException,
    InvalidInnLengthException,
    InvalidPaymentAddressException,
    TooLongEmailException,
    TooLongPaymentAddressException};
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс, описывающий сущность компании-продавца
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 17
 */
final class Company extends Entity
{
    use HasEmail;
    use HasInn;

    /**
     * Конструктор
     *
     * @param string $inn ИНН (1018)
     * @param SnoType $sno Система налогообложения продавца (1055)
     * @param string $paymentAddress Место расчётов (адрес интернет-магазина) (1187)
     * @param string $email Почта (1117)
     * @throws InvalidEmailException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws TooLongEmailException
     * @throws TooLongPaymentAddressException
     */
    public function __construct(
        string $inn,
        protected SnoType $sno,
        protected string $paymentAddress,
        string $email,
    ) {
        $this->setInn($inn)
            ->setPaymentAddress($paymentAddress)
            ->setEmail($email);
    }

    /**
     * Возвращает установленный тип налогообложения
     *
     * @return SnoType
     */
    public function getSno(): SnoType
    {
        return $this->sno;
    }

    /**
     * Устанавливает тип налогообложения
     *
     * @param SnoType $sno
     * @return $this
     */
    public function setSno(SnoType $sno): self
    {
        $this->sno = $sno;
        return $this;
    }

    /**
     * Возвращает установленный адрес места расчётов
     *
     * @return string
     */
    public function getPaymentAddress(): string
    {
        return $this->paymentAddress;
    }

    /**
     * Устанавливает адрес места расчётов
     *
     * @param string $paymentAddress
     * @return $this
     * @throws TooLongPaymentAddressException
     * @throws InvalidPaymentAddressException
     */
    public function setPaymentAddress(string $paymentAddress): self
    {
        $paymentAddress = trim($paymentAddress);
        if (empty($paymentAddress)) {
            throw new InvalidPaymentAddressException();
        } elseif (mb_strlen($paymentAddress) > Constraints::MAX_LENGTH_PAYMENT_ADDRESS) {
            throw new TooLongPaymentAddressException($paymentAddress);
        }
        $this->paymentAddress = $paymentAddress;
        return $this;
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'sno' => 'string',
        'email' => 'string',
        'inn' => 'string',
        'payment_address' => 'string',
    ])]
    public function jsonSerialize(): array
    {
        return [
            'inn' => $this->getInn(),
            'sno' => $this->getSno(),
            'payment_address' => $this->getPaymentAddress(),
            'email' => $this->getEmail(),
        ];
    }
}
