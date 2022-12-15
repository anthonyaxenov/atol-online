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

use AtolOnline\{
    Constraints,
    Enums\PaymentType,};
use AtolOnline\Exceptions\{
    NegativePaymentSumException,
    TooHighPaymentSumException,};
use JetBrains\PhpStorm\{
    ArrayShape,
    Pure};

/**
 * Класс, описывающий оплату
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 30
 */
final class Payment extends Entity
{
    /**
     * Конструктор
     *
     * @param PaymentType $type Тип оплаты
     * @param float $sum Сумма оплаты (1031, 1081, 1215, 1216, 1217)
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     */
    public function __construct(
        protected PaymentType $type,
        protected float $sum,
    ) {
        $this->setType($type)->setSum($sum);
    }

    /**
     * Возвращает установленный тип оплаты
     *
     * @return PaymentType
     */
    public function getType(): PaymentType
    {
        return $this->type;
    }

    /**
     * Устанавливает тип оплаты
     *
     * @param PaymentType $type
     * @return $this
     */
    public function setType(PaymentType $type): self
    {
        $this->type = $type;
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
        $sum > Constraints::MAX_COUNT_PAYMENT_SUM && throw new TooHighPaymentSumException($sum);
        $sum < 0 && throw new NegativePaymentSumException($sum);
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
