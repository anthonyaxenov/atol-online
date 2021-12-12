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

use AtolOnline\{
    Constants\Constraints,
    Enums\SnoTypes,
    Traits\HasEmail,
    Traits\HasInn
};
use AtolOnline\Exceptions\{
    InvalidEmailException,
    InvalidEnumValueException,
    InvalidInnLengthException,
    InvalidPaymentAddressException,
    TooLongEmailException,
    TooLongPaymentAddressException
};
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс, описывающий сущность компании-продавца
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 17
 */
final class Company extends Entity
{
    use HasEmail, HasInn;

    /**
     * @var string|null Система налогообложения продавца (1055)
     */
    protected ?string $sno;

    /**
     * @var string|null Место расчётов (адрес интернет-магазина) (1187)
     */
    protected ?string $payment_address;

    /**
     * Конструктор
     *
     * @param string $sno Система налогообложения продавца (1055)
     * @param string $inn ИНН (1018)
     * @param string $payment_address Место расчётов (адрес интернет-магазина) (1187)
     * @param string $email Почта (1117)
     * @throws InvalidEmailException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws InvalidEnumValueException
     * @throws TooLongEmailException
     * @throws TooLongPaymentAddressException
     */
    public function __construct(
        string $email, //TODO сделать необязательным здесь
        string $sno, //TODO сделать необязательным здесь
        string $inn,
        string $payment_address,
    ) {
        $this->setEmail($email)->setSno($sno)->setInn($inn)->setPaymentAddress($payment_address);
    }

    /**
     * Возвращает установленный тип налогообложения
     *
     * @return string
     */
    public function getSno(): string
    {
        return $this->sno;
    }

    /**
     * Устанавливает тип налогообложения
     *
     * @param string $sno
     * @return $this
     * @throws InvalidEnumValueException
     */
    public function setSno(string $sno): self
    {
        $sno = trim($sno);
        SnoTypes::isValid($sno) && $this->sno = $sno;
        return $this;
    }

    /**
     * Возвращает установленный адрес места расчётов
     *
     * @return string
     */
    public function getPaymentAddress(): string
    {
        return $this->payment_address;
    }

    /**
     * Устанавливает адрес места расчётов
     *
     * @param string $payment_address
     * @return $this
     * @throws TooLongPaymentAddressException
     * @throws InvalidPaymentAddressException
     */
    public function setPaymentAddress(string $payment_address): self
    {
        $payment_address = trim($payment_address);
        if (empty($payment_address)) {
            throw new InvalidPaymentAddressException();
        } elseif (mb_strlen($payment_address) > Constraints::MAX_LENGTH_PAYMENT_ADDRESS) {
            throw new TooLongPaymentAddressException($payment_address);
        }
        $this->payment_address = $payment_address;
        return $this;
    }

    /**
     * @inheritDoc
     * @throws InvalidEmailException
     * @throws InvalidEnumValueException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     */
    #[ArrayShape([
        'email' => 'string',
        'sno' => 'string',
        'inn' => 'string',
        'payment_address' => 'string',
    ])]
    public function jsonSerialize(): array
    {
        return [
            'email' => $this->email
                ? $this->getEmail()
                : throw new InvalidEmailException(),
            'sno' => $this->sno
                ? $this->getSno()
                : throw new InvalidEnumValueException(SnoTypes::class, 'null'),
            'inn' => $this->inn
                ? $this->getInn()
                : throw new InvalidInnLengthException(),
            'payment_address' => $this->payment_address
                ? $this->getPaymentAddress()
                : throw new InvalidPaymentAddressException(),
        ];
    }
}
