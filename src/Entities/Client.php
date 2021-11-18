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
    Exceptions\InvalidEmailException,
    Exceptions\InvalidInnLengthException,
    Exceptions\TooLongEmailException,
    Exceptions\TooLongNameException,
    Exceptions\TooLongPhoneException};

/**
 * Класс Client, описывающий сущность покупателя
 *
 * @package AtolOnline\Entities
 */
class Client extends Entity
{
    /**
     * @var string|null Наименование. Тег ФФД - 1227.
     */
    protected ?string $name = null;

    /**
     * @var string|null Email. Тег ФФД -  1008.
     */
    protected ?string $email = null;

    /**
     * @var string|null Телефон покупателя. Тег ФФД - 1008.
     */
    protected ?string $phone = null;

    /**
     * @var string|null ИНН. Тег ФФД -  1228.
     */
    protected ?string $inn = null;

    /**
     * Конструктор объекта покупателя
     *
     * @param string|null $name Наименование. Тег ФФД - 1227.
     * @param string|null $phone Email. Тег ФФД -  1008.
     * @param string|null $email Телефон покупателя. Тег ФФД - 1008.
     * @param string|null $inn ИНН. Тег ФФД -  1228.
     * @throws TooLongNameException Слишком длинное имя
     * @throws TooLongPhoneException Слишком длинный телефон
     * @throws TooLongEmailException Слишком длинный email
     * @throws InvalidEmailException Невалидный email
     * @throws InvalidInnLengthException Некорректная длина ИНН
     */
    public function __construct(
        ?string $name = null,
        ?string $email = null,
        ?string $phone = null,
        ?string $inn = null
    ) {
        $name && $this->setName($name);
        $email && $this->setEmail($email);
        $phone && $this->setPhone($phone);
        $inn && $this->setInn($inn);
    }

    /**
     * Возвращает наименование покупателя
     *
     * Тег ФФД - 1227
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
     * Тег ФФД - 1227
     *
     * @param string|null $name
     * @return $this
     * @throws TooLongNameException
     */
    public function setName(?string $name): Client
    {
        if (is_string($name)) {
            $name = preg_replace('/[\n\r\t]/', '', trim($name));
            if (mb_strlen($name) > Constraints::MAX_LENGTH_CLIENT_NAME) {
                throw new TooLongNameException($name, Constraints::MAX_LENGTH_CLIENT_NAME);
            }
        }
        $this->name = empty($name) ? null : $name;
        return $this;
    }

    /**
     * Возвращает установленный email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Устанавливает email
     *
     * @param string|null $email
     * @return $this
     * @throws TooLongEmailException Слишком длинный email
     * @throws InvalidEmailException Невалидный email
     */
    public function setEmail(?string $email): self
    {
        if (is_string($email)) {
            $email = preg_replace('/[\n\r\t]/', '', trim($email));
            if (mb_strlen($email) > Constraints::MAX_LENGTH_EMAIL) {
                throw new TooLongEmailException($email, Constraints::MAX_LENGTH_EMAIL);
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                throw new InvalidEmailException($email);
            }
        }
        $this->email = empty($email) ? null : $email;
        return $this;
    }

    /**
     * Возвращает установленный телефон
     *
     * Тег ФФД - 1008
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
     * Тег ФФД - 1008
     *
     * @param string|null $phone Номер телефона
     * @return $this
     * @throws TooLongPhoneException
     */
    public function setPhone(?string $phone): Client
    {
        if (is_string($phone)) {
            $phone = preg_replace('/[^\d]/', '', trim($phone));
            if (mb_strlen($phone) > Constraints::MAX_LENGTH_CLIENT_PHONE) {
                throw new TooLongPhoneException($phone, Constraints::MAX_LENGTH_CLIENT_PHONE);
            }
        }
        $this->phone = empty($phone) ? null : "+$phone";
        return $this;
    }

    /**
     * Возвращает установленный ИНН
     *
     * @return string|null
     */
    public function getInn(): ?string
    {
        return $this->inn;
    }

    /**
     * Устанавливает ИНН
     *
     * @param string|null $inn
     * @return $this
     * @throws InvalidInnLengthException Некорректная длина ИНН
     */
    public function setInn(?string $inn): self
    {
        if (is_string($inn)) {
            $inn = preg_replace('/[^\d]/', '', trim($inn));
            if (preg_match_all(Constraints::PATTERN_INN, $inn) === 0) {
                throw new InvalidInnLengthException($inn);
            }
        }
        $this->inn = empty($inn) ? null : $inn;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): object
    {
        $json = [];
        $this->getName() && $json['name'] = $this->getName();
        $this->getEmail() && $json['email'] = $this->getEmail();
        $this->getPhone() && $json['phone'] = $this->getPhone();
        $this->getInn() && $json['inn'] = $this->getInn();
        return (object)$json;
    }
}
