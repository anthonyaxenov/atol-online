<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use AtolOnline\{Exceptions\AtolEmailTooLongException,
    Exceptions\AtolEmailValidateException,
    Exceptions\AtolInnWrongLengthException,
    Exceptions\AtolPaymentAddressTooLongException,
    Traits\HasEmail,
    Traits\HasInn
};

/**
 * Класс, описывающий сущность компании-продавца
 *
 * @package AtolOnline\Entities
 */
class Company extends AtolEntity
{
    use
        /**
         * Продавец должен иметь почту. Тег ФФД - 1117.
         */
        HasEmail,
        
        /**
         * Продавец должен иметь ИНН. Тег ФФД - 1018.
         */
        HasInn;
    
    /**
     * @var string Система налогообложения продавца. Тег ФФД - 1055.
     */
    protected $sno;
    
    /**
     * @var string Место расчётов (адрес интернет-магазина). Тег ФФД - 1187.
     */
    protected $payment_address;
    
    /**
     * Company constructor.
     *
     * @param string|null $sno
     * @param string|null $inn
     * @param string|null $paymentAddress
     * @param string|null $email
     * @throws AtolEmailTooLongException
     * @throws AtolEmailValidateException
     * @throws AtolInnWrongLengthException
     * @throws AtolPaymentAddressTooLongException
     */
    public function __construct(string $sno = null, string $inn = null, string $paymentAddress = null, string $email = null)
    {
        if ($sno) {
            $this->setSno($sno);
        }
        if ($inn) {
            $this->setInn($inn);
        }
        if ($paymentAddress) {
            $this->setPaymentAddress($paymentAddress);
        }
        if ($email) {
            $this->setEmail($email);
        }
    }
    
    /**
     * Возвращает установленный тип налогообложения. Тег ФФД - 1055.
     *
     * @return string
     */
    public function getSno()
    {
        return $this->sno;
    }
    
    /**
     * Устанавливает тип налогообложения. Тег ФФД - 1055.
     *
     * @param string $sno
     * @return $this
     */
    public function setSno(string $sno)
    {
        $this->sno = trim($sno);
        return $this;
    }
    
    /**
     * Возвращает установленный адрес места расчётов. Тег ФФД - 1187.
     *
     * @return string
     */
    public function getPaymentAddress()
    {
        return $this->payment_address;
    }
    
    /**
     * Устанавливает адрес места расчётов. Тег ФФД - 1187.
     *
     * @param string $payment_address
     * @return $this
     * @throws AtolPaymentAddressTooLongException
     */
    public function setPaymentAddress(string $payment_address)
    {
        $payment_address = trim($payment_address);
        if (strlen($payment_address) > 256) {
            throw new AtolPaymentAddressTooLongException($payment_address, 256);
        }
        $this->payment_address = $payment_address;
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'email' => $this->getEmail(),
            'sno' => $this->getSno(),
            'inn' => $this->getInn(),
            'payment_address' => $this->getPaymentAddress(),
        ];
    }
}