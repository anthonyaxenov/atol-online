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

use AtolOnline\Constants\Constraints;
use AtolOnline\Enums\PaymentTypes;
use AtolOnline\Exceptions\InvalidEnumValueException;
use AtolOnline\Exceptions\NegativePaymentSumException;
use AtolOnline\Exceptions\TooHighPaymentSumException;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Класс, описывающий оплату
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 30
 */
class Payment extends Entity
{
    /**
     * @var int Тип оплаты
     */
    protected int $type;

    /**
     * @var float Сумма оплаты (1031, 1081, 1215, 1216, 1217)
     */
    protected float $sum;

    /**
     * Конструктор
     *
     * @param int $type Тип оплаты
     * @param float $sum Сумма оплаты
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function __construct(int $type, float $sum)
    {
        $this->setType($type)->setSum($sum);
    }

    /**
     * Возвращает установленный тип оплаты
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Устанавливает тип оплаты
     *
     * @param int $type
     * @return $this
     * @throws InvalidEnumValueException
     */
    public function setType(int $type): self
    {
        PaymentTypes::isValid($type) && $this->type = $type;
        return $this;
    }

    /**
     * Возвращает установленную сумму оплаты
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
     * @throws TooHighPaymentSumException
     * @throws NegativePaymentSumException
     */
    public function setSum(float $sum): self
    {
        $sum = round($sum, 2);
        if ($sum > Constraints::MAX_COUNT_PAYMENT_SUM) {
            throw new TooHighPaymentSumException($sum);
        }
        if ($sum < 0) {
            throw new NegativePaymentSumException($sum);
        }
        $this->sum = $sum;
        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    #[ArrayShape(['type' => 'int', 'sum' => 'float'])]
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->getType(),
            'sum' => $this->getSum(),
        ];
    }
}
