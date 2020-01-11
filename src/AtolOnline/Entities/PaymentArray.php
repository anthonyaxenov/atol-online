<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use AtolOnline\Exceptions\AtolTooManyPaymentsException;

/**
 * Класс, описывающий массив оплат
 *
 * @package AtolOnline\Entities
 */
class PaymentArray extends AtolEntity
{
    /**
     * Максимальное количество элементов в массиве
     */
    const MAX_COUNT = 10;
    
    /**
     * @var Payment[] Массив оплат
     */
    private $payments = [];
    
    /**
     * ItemArray constructor.
     *
     * @param Payment[]|null $payments Массив оплат
     * @throws AtolTooManyPaymentsException Слишком много оплат
     */
    public function __construct(array $payments = null)
    {
        if ($payments) {
            $this->set($payments);
        }
    }
    
    /**
     * Устанавливает массив оплат
     *
     * @param Payment[] $payments
     * @return $this
     * @throws AtolTooManyPaymentsException Слишком много оплат
     */
    public function set(array $payments)
    {
        if ($this->validateCount($payments)) {
            $this->payments = $payments;
        }
        return $this;
    }
    
    /**
     * Добавляет новую оплату к заданным
     *
     * @param Payment $payment Объект оплаты
     * @return $this
     * @throws AtolTooManyPaymentsException Слишком много оплат
     */
    public function add(Payment $payment)
    {
        if ($this->validateCount()) {
            $this->payments[] = $payment;
        }
        return $this;
    }
    
    /**
     * Возвращает массив оплат
     *
     * @return Payment[]
     */
    public function get()
    {
        return $this->payments;
    }
    
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $result = [];
        foreach ($this->get() as $payment) {
            $result[] = $payment->jsonSerialize();
        }
        return $result;
    }
    
    /**
     * Проверяет количество элементов в массиве
     *
     * @param Payment[]|null $payments Если передать массив, то проверит количество его элементов.
     *                                 Иначе проверит количество уже присвоенных элементов.
     * @return bool
     * @throws AtolTooManyPaymentsException Слишком много оплат
     */
    protected function validateCount(array $payments = null)
    {
        if (($payments && is_array($payments) && count($payments) >= self::MAX_COUNT) || count($this->payments) == self::MAX_COUNT) {
            throw new AtolTooManyPaymentsException(self::MAX_COUNT);
        }
        return true;
    }
}