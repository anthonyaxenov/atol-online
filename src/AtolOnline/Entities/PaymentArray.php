<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use AtolOnline\Api\SellSchema;
use AtolOnline\Exceptions\AtolTooFewPaymentsException;
use AtolOnline\Exceptions\AtolTooManyPaymentsException;

/**
 * Класс, описывающий массив оплат
 *
 * @package AtolOnline\Entities
 */
class PaymentArray extends Entity
{
    /**
     * @var Payment[] Массив оплат
     */
    private $payments = [];
    
    /**
     * ItemArray constructor.
     *
     * @param Payment[]|null $payments Массив оплат
     * @throws AtolTooFewPaymentsException  Слишком мало оплат
     * @throws AtolTooManyPaymentsException Слишком много оплат
     */
    public function __construct(?array $payments = null)
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
     * @throws AtolTooFewPaymentsException  Слишком мало оплат
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
     * @throws AtolTooFewPaymentsException  Слишком мало оплат
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
     * Проверяет количество налоговых ставок
     *
     * @param Payment[]|null $payments Если передать массив, то проверит количество его элементов.
     *                                 Иначе проверит количество уже присвоенных элементов.
     * @return bool true если всё хорошо, иначе выбрасывает исключение
     * @throws AtolTooFewPaymentsException  Слишком мало оплат
     * @throws AtolTooManyPaymentsException Слишком много оплат
     */
    protected function validateCount(?array $payments = null)
    {
        return empty($payments)
            ? $this->checkCount($this->payments)
            : $this->checkCount($payments);
    }
    
    /**
     * Проверяет количество элементов в указанном массиве
     *
     * @param array $elements
     * @return bool true если всё хорошо, иначе выбрасывает исключение
     * @throws AtolTooFewPaymentsException  Слишком мало оплат
     * @throws AtolTooManyPaymentsException Слишком много оплат
     */
    protected function checkCount(?array $elements = null)
    {
        $min_count = SellSchema::get()->receipt->properties->payments->minItems;
        $max_count = SellSchema::get()->receipt->properties->payments->maxItems;
        if (empty($elements) || count($elements) < $min_count) {
            throw new AtolTooFewPaymentsException($min_count);
        } elseif (count($elements) >= $max_count) {
            throw new AtolTooManyPaymentsException($max_count);
        } else {
            return true;
        }
    }
}