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

use AtolOnline\Constraints;
use AtolOnline\Exceptions\{
    InvalidEmailException,
    InvalidInnLengthException,
    InvalidPhoneException,
    TooLongClientNameException,
    TooLongEmailException};
use AtolOnline\Traits\{
    HasEmail,
    HasInn};
use JetBrains\PhpStorm\Pure;

/**
 * Класс, описывающий покупателя
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 17
 */
final class Client extends Entity
{
    use HasEmail;
    use HasInn;

    /**
     * Конструктор объекта покупателя
     *
     * @param string|null $name Наименование (1227)
     * @param string|null $email Телефон (1008)
     * @param string|null $phone Email (1008)
     * @param string|null $inn ИНН (1228)
     * @throws InvalidEmailException
     * @throws InvalidInnLengthException
     * @throws InvalidPhoneException
     * @throws TooLongClientNameException
     * @throws TooLongEmailException
     */
    public function __construct(
        protected ?string $name = null,
        protected ?string $phone = null,
        ?string $email = null,
        ?string $inn = null
    ) {
        !is_null($name) && $this->setName($name);
        !is_null($email) && $this->setEmail($email);
        !is_null($phone) && $this->setPhone($phone);
        !is_null($inn) && $this->setInn($inn);
    }

    /**
     * Возвращает наименование покупателя
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Устанавливает наименование покупателя
     *
     * @param string|null $name
     * @return $this
     * @throws TooLongClientNameException
     */
    public function setName(?string $name): self
    {
        if (is_string($name)) {
            $name = preg_replace('/[\n\r\t]/', '', trim($name));
            if (mb_strlen($name) > Constraints::MAX_LENGTH_CLIENT_NAME) {
                throw new TooLongClientNameException($name);
            }
        }
        $this->name = $name ?: null;
        return $this;
    }

    /**
     * Возвращает установленный телефон
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Устанавливает телефон
     *
     * @param string|null $phone Номер телефона
     * @return $this
     * @throws InvalidPhoneException
     */
    public function setPhone(?string $phone): self
    {
        if (is_string($phone)) {
            $phone = preg_replace('/\D/', '', trim($phone));
            if (preg_match(Constraints::PATTERN_PHONE, $phone) !== 1) {
                throw new InvalidPhoneException($phone);
            }
        }
        $this->phone = empty($phone) ? null : "+$phone";
        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function jsonSerialize(): array
    {
        $json = [];
        !is_null($this->getName()) && $json['name'] = $this->getName();
        !is_null($this->getPhone()) && $json['phone'] = $this->getPhone();
        !is_null($this->getEmail()) && $json['email'] = $this->getEmail();
        !is_null($this->getInn()) && $json['inn'] = $this->getInn();
        return $json;
    }
}
