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
    Exceptions\InvalidEmailException,
    Exceptions\InvalidEnumValueException,
    Exceptions\InvalidInnLengthException,
    Exceptions\InvalidPaymentAddressException,
    Exceptions\TooLongEmailException,
    Exceptions\TooLongPaymentAddressException};

/**
 * Класс, описывающий сущность компании-продавца
 *
 * @package AtolOnline\Entities
 */
class Company extends Entity
{
    /**
     * @var string|null Почта. Тег ФФД -  1117.
     */
    protected ?string $email;

    /**
     * @var string|null Система налогообложения продавца. Тег ФФД - 1055.
     */
    protected ?string $sno;

    /**
     * @var string|null ИНН. Тег ФФД -  1018.
     */
    protected ?string $inn;

    /**
     * @var string|null Место расчётов (адрес интернет-магазина). Тег ФФД - 1187.
     */
    protected ?string $payment_address;

    /**
     * Company constructor.
     *
     * @param string $sno
     * @param string $inn
     * @param string $payment_address
     * @param string $email
     * @throws InvalidEmailException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws InvalidEnumValueException
     * @throws TooLongEmailException
     * @throws TooLongPaymentAddressException
     */
    public function __construct(
        string $email,
        string $sno,
        string $inn,
        string $payment_address,
    ) {
        $this->setEmail($email);
        $this->setSno($sno);
        $this->setInn($inn);
        $this->setPaymentAddress($payment_address);
    }

    /**
     * Возвращает установленный email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Устанавливает email
     *
     * @param string $email
     * @return $this
     * @throws TooLongEmailException Слишком длинный email
     * @throws InvalidEmailException Невалидный email
     */
    public function setEmail(string $email): self
    {
        $email = preg_replace('/[\n\r\t]/', '', trim($email));
        if (mb_strlen($email) > Constraints::MAX_LENGTH_EMAIL) {
            throw new TooLongEmailException($email);
        } elseif (empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidEmailException($email);
        }
        $this->email = $email;
        return $this;
    }

    /**
     * Возвращает установленный тип налогообложения
     *
     * Тег ФФД - 1055
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
     * Тег ФФД - 1055
     *
     * @param string $sno
     * @return $this
     * @throws InvalidEnumValueException
     */
    public function setSno(string $sno): self
    {
        $sno = trim($sno);
        SnoTypes::isValid($sno);
        $this->sno = $sno;
        return $this;
    }

    /**
     * Возвращает установленный ИНН
     *
     * Тег ФФД - 1018
     *
     * @return string
     */
    public function getInn(): string
    {
        return $this->inn;
    }

    /**
     * Устанавливает ИНН
     *
     * Тег ФФД - 1018
     *
     * @param string $inn
     * @return $this
     * @throws InvalidInnLengthException
     */
    public function setInn(string $inn): self
    {
        $inn = preg_replace('/[^\d]/', '', trim($inn));
        if (empty($inn) || preg_match_all(Constraints::PATTERN_INN, $inn) === 0) {
            throw new InvalidInnLengthException($inn);
        }
        $this->inn = $inn;
        return $this;
    }

    /**
     * Возвращает установленный адрес места расчётов
     *
     * Тег ФФД - 1187
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
     * Тег ФФД - 1187
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
    public function jsonSerialize(): object
    {
        return (object)[
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