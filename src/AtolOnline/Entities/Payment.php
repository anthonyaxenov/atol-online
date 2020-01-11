<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use AtolOnline\Constants\PaymentTypes;

/**
 * Класс, описывающий оплату. Тег ФФД - 1031, 1081, 1215, 1216, 1217.
 *
 * @package AtolOnline\Entities
 */
class Payment extends AtolEntity
{
    /**
     * @var int Тип оплаты
     */
    protected $type;
    
    /**
     * @var float Сумма оплаты
     */
    protected $sum;
    
    /**
     * Payment constructor.
     *
     * @param int   $payment_type Тип оплаты
     * @param float $sum          Сумма оплаты
     */
    public function __construct(int $payment_type = PaymentTypes::ELECTRON, float $sum = 0.0)
    {
        $this->setType($payment_type);
        $this->setSum($sum);
    }
    
    /**
     * Возвращает тип оплаты. Тег ФФД - 1031, 1081, 1215, 1216, 1217.
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }
    
    /**
     * Устанавливает тип оплаты. Тег ФФД - 1031, 1081, 1215, 1216, 1217.
     *
     * @param int $type
     * @return $this
     */
    public function setType(int $type)
    {
        $this->type = $type;
        return $this;
    }
    
    /**
     * Возвращает сумму оплаты
     *
     * @return float
     */
    public function getSum(): float
    {
        return $this->sum;
    }
    
    /**
     * Устанавливает сумму оплаты
     *
     * @param float $sum
     * @return $this
     */
    public function setSum(float $sum)
    {
        $this->sum = $sum;
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'type' => $this->getType(),
            'sum' => $this->getSum(),
        ];
    }
}
