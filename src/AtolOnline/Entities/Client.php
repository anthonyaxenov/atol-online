<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use AtolOnline\{Exceptions\AtolNameTooLongException, Exceptions\AtolPhoneTooLongException, Traits\HasEmail, Traits\HasInn};

/**
 * Класс Client, описывающий сущность покупателя
 *
 * @package AtolOnline\Entities
 */
class Client extends Entity
{
    use
        /**
         * Покупатель может иметь почту. Тег ФФД - 1008.
         */
        HasEmail,
        
        /**
         * Покупатель может иметь ИНН. Тег ФФД - 1228.
         */
        HasInn;
    
    /**
     * @var string Телефон покупателя. Тег ФФД - 1008.
     */
    protected $phone;
    
    /**
     * @var string Имя покупателя. Тег ФФД - 1227.
     */
    protected $name;
    
    /**
     * Client constructor.
     *
     * @param string|null $name  Наименование
     * @param string|null $phone Телефон
     * @param string|null $email Email
     * @param string|null $inn   ИНН
     * @throws \AtolOnline\Exceptions\AtolEmailTooLongException
     * @throws \AtolOnline\Exceptions\AtolEmailValidateException
     * @throws \AtolOnline\Exceptions\AtolInnWrongLengthException
     * @throws \AtolOnline\Exceptions\AtolNameTooLongException
     * @throws \AtolOnline\Exceptions\AtolPhoneTooLongException
     */
    public function __construct(?string $name = null, ?string $phone = null, ?string $email = null, ?string $inn = null)
    {
        if ($name) {
            $this->setName($name);
        }
        if ($email) {
            $this->setEmail($email);
        }
        if ($phone) {
            $this->setPhone($phone);
        }
        if ($inn) {
            $this->setInn($inn);
        }
    }
    
    /**
     * Возвращает имя покупателя. Тег ФФД - 1227.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Устанавливает имя покупателя
     * Тег ФФД - 1227.
     *
     * @param string $name
     * @return $this
     * @throws AtolNameTooLongException
     */
    public function setName(string $name)
    {
        $name = trim($name);
        if (strlen($name) > 256) {
            throw new AtolNameTooLongException($name, 256);
        }
        $this->name = $name;
        return $this;
    }
    
    /**
     * Возвращает телефон покупателя.
     * Тег ФФД - 1008.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone ?? '';
    }
    
    /**
     * Устанавливает телефон покупателя.
     * Тег ФФД - 1008.
     * Входная строка лишается всех знаков, кроме цифр и знака '+'.
     *
     * @param string $phone
     * @return $this
     * @throws AtolPhoneTooLongException
     */
    public function setPhone(string $phone)
    {
        $phone = preg_replace("/[^0-9+]/", '', $phone);
        if (strlen($phone) > 64) {
            throw new AtolPhoneTooLongException($phone, 64);
        }
        $this->phone = $phone;
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $json = [];
        if ($this->getName()) {
            $json['name'] = $this->getName() ?? '';
        }
        if ($this->getEmail()) {
            $json['email'] = $this->getEmail() ?? '';
        }
        if ($this->getPhone()) {
            $json['phone'] = $this->getPhone() ?? '';
        }
        if ($this->getInn()) {
            $json['inn'] = $this->getInn() ?? '';
        }
        return $json;
    }
}
