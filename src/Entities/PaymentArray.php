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

use AtolOnline\Exceptions\TooManyPaymentsException;

/**
 * Класс, описывающий массив оплат
 *
 * @package AtolOnline\Entities
 */
class PaymentArray extends Entity
{
    /**
     * Максимальное количество элементов массива
     */
    public const MAX_COUNT = 10;
    
    /**
     * @var Payment[] Массив оплат
     */
    private array $payments = [];

    /**
     * ItemArray constructor.
     *
     * @param Payment[]|null $payments Массив оплат
     * @throws TooManyPaymentsException Слишком много оплат
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
     * @throws TooManyPaymentsException Слишком много оплат
     */
    public function set(array $payments): PaymentArray
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
     * @throws TooManyPaymentsException Слишком много оплат
     */
    public function add(Payment $payment): PaymentArray
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
    public function get(): array
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
     * @throws TooManyPaymentsException Слишком много оплат
     */
    protected function validateCount(?array $payments = null): bool
    {
        if ((!empty($payments) && count($payments) >= self::MAX_COUNT) || count($this->payments) >= self::MAX_COUNT) {
            throw new TooManyPaymentsException(count($payments), self::MAX_COUNT);
        }
        return true;
    }
}