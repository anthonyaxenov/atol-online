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

use AtolOnline\Enums\VatTypes;
use AtolOnline\Helpers;

/**
 * Класс, описывающий ставку НДС
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 25, 31
 */
class Vat extends Entity
{
    /**
     * @var string Тип ставки НДС (1199, 1105, 1104, 1103, 1102, 1107, 1106)
     */
    private string $type;

    /**
     * @var float Сумма в рублях, от которой пересчитывается размер НДС
     */
    private float $sum;

    /**
     * Конструктор
     *
     * @param string $type Тип ставки НДС (1199, 1105, 1104, 1103, 1102, 1107, 1106)
     * @param float $rubles Исходная сумма в рублях, от которой нужно расчитать размер НДС
     */
    public function __construct(string $type, float $rubles)
    {
        $this->setType($type)->setSum($rubles);
    }

    /**
     * Устанавливает тип ставки НДС
     * Автоматически пересчитывает итоговый размер НДС от исходной суммы.
     *
     * @param string $type Тип ставки НДС
     * @return $this
     */
    public function setType(string $type): self
    {
        $type = trim($type);
        VatTypes::isValid($type) && $this->type = $type;
        return $this;
    }

    /**
     * Возвращает тип ставки НДС
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Возвращает исходную сумму, от которой расчитывается размер налога
     *
     * @return float
     */
    public function getSum(): float
    {
        return $this->sum;
    }

    /**
     * Устанавливает исходную сумму, от которой будет расчитываться итоговый размер НДС.
     * Автоматически пересчитывает итоговый размер НДС от исходной суммы.
     *
     * @param float $rubles Сумма в рублях за предмет расчёта, из которой высчитывается размер НДС
     * @return $this
     */
    public function setSum(float $rubles): self
    {
        $this->sum = $rubles;
        return $this;
    }

    /**
     * Возвращает sdрасчитанный итоговый размер ставки НДС в рублях
     *
     * @return float
     * @see https://nalog-nalog.ru/nds/nalogovaya_baza_nds/kak-schitat-nds-pravilno-vychislyaem-20-ot-summy-primer-algoritm/
     * @see  https://glavkniga.ru/situations/k500734
     * @see https://www.b-kontur.ru/nds-kalkuljator-online
     */
    public function getCalculated(): float
    {
        $kopeks = Helpers::toKop($this->sum);
        return Helpers::toRub(match ($this->getType()) {
            VatTypes::VAT10 => $kopeks * 10 / 100,
            VatTypes::VAT18 => $kopeks * 18 / 100,
            VatTypes::VAT20 => $kopeks * 20 / 100,
            VatTypes::VAT110 => $kopeks * 10 / 110,
            VatTypes::VAT118 => $kopeks * 18 / 118,
            VatTypes::VAT120 => $kopeks * 20 / 120,
            default => 0,
        });
    }

    /**
     * Прибавляет указанную сумму к исходной
     *
     * @param float $rubles
     * @return $this
     */
    public function addSum(float $rubles): self
    {
        $this->sum += $rubles;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->getType(),
            'sum' => $this->getCalculated(),
        ];
    }
}
