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
    Exceptions\TooLongClientContactException,
    Exceptions\TooLongClientNameException,
    Exceptions\TooLongEmailException};

/**
 * Класс Client, описывающий сущность покупателя
 *
 * @package AtolOnline\Entities
 */
class Client extends Entity
{
    /**
     * @var string|null Наименование (1227)
     */
    protected ?string $name = null;

    /**
     * @var string|null Email (1008)
     */
    protected ?string $email = null;

    /**
     * @var string|null Телефон (1008)
     */
    protected ?string $phone = null;

    /**
     * @var string|null ИНН (1228)
     */
    protected ?string $inn = null;

    /**
     * Конструктор объекта покупателя
     *
     * @param string|null $name Наименование (1227)
     * @param string|null $phone Email (1008)
     * @param string|null $email Телефон (1008)
     * @param string|null $inn ИНН (1228)
     * @throws TooLongClientNameException
     * @throws TooLongClientContactException
     * @throws TooLongEmailException
     * @throws InvalidEmailException
     * @throws InvalidInnLengthException
     */
    public function __construct(
        ?string $name = null,
        ?string $email = null,
        ?string $phone = null,
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
     * @todo улучшить валидацию по Constraints::PATTERN_PHONE
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
                throw new TooLongEmailException($email);
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
     * @throws TooLongClientContactException
     */
    public function setPhone(?string $phone): self
    {
        if (is_string($phone)) {
            $phone = preg_replace('/[^\d]/', '', trim($phone));
            if (mb_strlen($phone) > Constraints::MAX_LENGTH_CLIENT_CONTACT) {
                throw new TooLongClientContactException($phone);
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
    public function jsonSerialize(): array
    {
        $json = [];
        $this->getName() && $json['name'] = $this->getName();
        $this->getEmail() && $json['email'] = $this->getEmail();
        $this->getPhone() && $json['phone'] = $this->getPhone();
        $this->getInn() && $json['inn'] = $this->getInn();
        return $json;
    }
}
