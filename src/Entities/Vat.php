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

use AtolOnline\Constants\VatTypes;

/**
 * Класс, описывающий ставку НДС
 *
 * @package AtolOnline\Entities
 */
class Vat extends Entity
{
    /**
     * @var string Выбранный тип ставки НДС. Тег ФФД - 1199, 1105, 1104, 1103, 1102, 1107, 1106.
     */
    private string $type;
    
    /**
     * @var int Сумма в копейках, от которой пересчитывается размер НДС
     */
    private int $sum_original = 0;
    
    /**
     * @var int Сумма НДС в копейках
     */
    private int $sum_final = 0;
    
    /**
     * Vat constructor.
     *
     * @param string     $type   Тип ставки НДС
     * @param float|null $rubles Исходная сумма в рублях, от которой нужно расчитать размер НДС
     */
    public function __construct(string $type = VatTypes::NONE, float $rubles = null)
    {
        $this->type = $type;
        if ($rubles) {
            $this->setSum($rubles);
        }
    }
    
    /**
     * Устанавливает:
     * размер НДС от суммы в копейках
     *
     * @param string $type   Тип ставки НДС
     * @param int    $kopeks Копейки
     * @return float|int
     * @see https://nalog-nalog.ru/nds/nalogovaya_baza_nds/kak-schitat-nds-pravilno-vychislyaem-20-ot-summy-primer-algoritm/
     * @see https://glavkniga.ru/situations/k500734
     * @see https://www.b-kontur.ru/nds-kalkuljator-online
     */
    protected static function calculator(string $type, int $kopeks)
    {
        switch ($type) {
            case VatTypes::NONE:
            case VatTypes::VAT0:
                return 0;
            case VatTypes::VAT10:
                //return $kopeks * 10 / 100;
            case VatTypes::VAT110:
                return $kopeks * 10 / 110;
            case VatTypes::VAT18:
                //return $kopeks * 18 / 100;
            case VatTypes::VAT118:
                return $kopeks * 18 / 118;
            case VatTypes::VAT20:
                //return $kopeks * 20 / 100;
            case VatTypes::VAT120:
                return $kopeks * 20 / 120;
        }
        return 0;
    }
    
    /**
     * Возвращает тип ставки НДС. Тег ФФД - 1199, 1105, 1104, 1103, 1102, 1107, 1106.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    
    /**
     * Устанавливает тип ставки НДС. Тег ФФД - 1199, 1105, 1104, 1103, 1102, 1107, 1106.
     * Автоматически пересчитывает итоговый размер НДС от исходной суммы.
     *
     * @param string $type Тип ставки НДС
     * @return $this
     */
    public function setType(string $type): Vat
    {
        $this->type = $type;
        $this->setFinal();
        return $this;
    }
    
    /**
     * Возвращает расчитанный итоговый размер ставки НДС в рублях. Тег ФФД - 1200.
     *
     * @return float
     */
    public function getFinalSum(): float
    {
        return rubles($this->sum_final);
    }
    
    /**
     * Устанавливает исходную сумму, от которой будет расчитываться итоговый размер НДС.
     * Автоматически пересчитывает итоговый размер НДС от исходной суммы.
     *
     * @param float $rubles Сумма в рублях за предмет расчёта, из которой высчитывается размер НДС
     * @return $this
     */
    public function setSum(float $rubles): Vat
    {
        $this->sum_original = kopeks($rubles);
        $this->setFinal();
        return $this;
    }
    
    /**
     * Возвращает исходную сумму, от которой расчитывается размер налога
     *
     * @return float
     */
    public function getSum(): float
    {
        return rubles($this->sum_original);
    }
    
    /**
     * Прибавляет указанную сумму к общей исходной сумме.
     * Автоматически пересчитывает итоговый размер НДС от новой исходной суммы.
     *
     * @param float $rubles
     * @return $this
     */
    public function addSum(float $rubles): Vat
    {
        $this->sum_original += kopeks($rubles);
        $this->setFinal();
        return $this;
    }
    
    /**
     * Расчитывает и возвращает размер НДС от указанной суммы в рублях.
     * Не изменяет итоговый размер НДС.
     *
     * @param float|null $rubles
     * @return float
     */
    public function calc(float $rubles): float
    {
        return rubles(self::calculator($this->type, kopeks($rubles)));
    }
    
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'type' => $this->getType(),
            'sum' => $this->getFinalSum(),
        ];
    }
    
    /**
     * Расчитывает и устанавливает итоговый размер ставки от исходной суммы в копейках
     */
    protected function setFinal(): Vat
    {
        $this->sum_final = self::calculator($this->type, $this->sum_original);
        return $this;
    }
}