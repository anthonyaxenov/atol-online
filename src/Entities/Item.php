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

use AtolOnline\{
    Constants\Constraints,
    Exceptions\TooHighPriceException,
    Exceptions\TooLongItemNameException,
    Exceptions\TooLongUnitException,
    Exceptions\TooLongUserdataException,
    Exceptions\TooManyException};

/**
 * Предмет расчёта (товар, услуга)
 *
 * @package AtolOnline\Entities
 */
class Item extends Entity
{
    /**
     * @var string Наименование. Тег ФФД - 1030.
     */
    protected string $name;

    /**
     * @var int Цена в копейках (с учётом скидок и наценок). Тег ФФД - 1079.
     */
    protected int $price = 0;

    /**
     * @var float Количество, вес. Тег ФФД - 1023.
     */
    protected float $quantity = 0.0;

    /**
     * @var float Сумма в копейках. Тег ФФД - 1043.
     */
    protected float $sum = 0;

    /**
     * @var string Единица измерения количества. Тег ФФД - 1197.
     */
    protected string $measurement_unit;

    /**
     * @var Vat|null Ставка НДС
     */
    protected ?Vat $vat;

    /**
     * @var string Признак способа расчёта. Тег ФФД - 1214.
     */
    protected string $payment_method;

    /**
     * @var string Признак объекта расчёта. Тег ФФД - 1212.
     */
    protected string $payment_object;

    /**
     * @var string Дополнительный реквизит. Тег ФФД - 1191.
     */
    protected string $user_data;

    /**
     * Item constructor.
     *
     * @param string|null $name Наименование
     * @param float|null $price Цена за одну единицу
     * @param float|null $quantity Количество
     * @param string|null $measurement_unit Единица измерения
     * @param string|null $vat_type Ставка НДС
     * @param string|null $payment_object Признак
     * @param string|null $payment_method Способ расчёта
     * @throws TooLongItemNameException Слишком длинное наименование
     * @throws TooHighPriceException Слишком высокая цена за одну единицу
     * @throws TooManyException Слишком большое количество
     * @throws TooLongUnitException Слишком длинное название единицы измерения
     */
    public function __construct(
        ?string $name = null,
        ?float $price = null,
        ?float $quantity = null,
        ?string $measurement_unit = null,
        ?string $vat_type = null,
        ?string $payment_object = null,
        ?string $payment_method = null
    ) {
        if ($name) {
            $this->setName($name);
        }
        if ($price) {
            $this->setPrice($price);
        }
        if ($quantity) {
            $this->setQuantity($quantity);
        }
        if ($measurement_unit) {
            $this->setMeasurementUnit($measurement_unit);
        }
        if ($vat_type) {
            $this->setVatType($vat_type);
        }
        if ($payment_object) {
            $this->setPaymentObject($payment_object);
        }
        if ($payment_method) {
            $this->setPaymentMethod($payment_method);
        }
    }

    /**
     * Возвращает наименование. Тег ФФД - 1030.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Устаналивает наименование. Тег ФФД - 1030.
     *
     * @param string $name Наименование
     * @return $this
     * @throws TooLongItemNameException Слишком длинное имя/наименование
     */
    public function setName(string $name): self
    {
        $name = trim($name);
        if (mb_strlen($name) > Constraints::MAX_LENGTH_ITEM_NAME) {
            throw new TooLongItemNameException($name, Constraints::MAX_LENGTH_ITEM_NAME);
        }
        $this->name = $name;
        return $this;
    }

    /**
     * Возвращает цену в рублях. Тег ФФД - 1079.
     *
     * @return float
     */
    public function getPrice(): float
    {
        return rubles($this->price);
    }

    /**
     * Устанавливает цену в рублях. Тег ФФД - 1079.
     *
     * @param float $rubles Цена за одну единицу в рублях
     * @return $this
     * @throws TooHighPriceException Слишком высокая цена за одну единицу
     */
    public function setPrice(float $rubles): Item
    {
        if ($rubles > 42949672.95) {
            throw new TooHighPriceException($rubles, 42949672.95);
        }
        $this->price = kopeks($rubles);
        $this->calcSum();
        return $this;
    }

    /**
     * Возвращает количество. Тег ФФД - 1023.
     *
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * Устанавливает количество. Тег ФФД - 1023.
     *
     * @param float $quantity Количество
     * @param string|null $measurement_unit Единица измерения количества
     * @return $this
     * @throws TooManyException Слишком большое количество
     * @throws TooHighPriceException Слишком высокая общая стоимость
     * @throws TooLongUnitException Слишком длинное название единицы измерения
     */
    public function setQuantity(float $quantity, string $measurement_unit = null): Item
    {
        $quantity = round($quantity, 3);
        if ($quantity > 99999.999) {
            throw new TooManyException($quantity, 99999.999);
        }
        $this->quantity = $quantity;
        $this->calcSum();
        if ($measurement_unit) {
            $this->setMeasurementUnit($measurement_unit);
        }
        return $this;
    }

    /**
     * Возвращает заданную единицу измерения количества. Тег ФФД - 1197.
     *
     * @return string
     */
    public function getMeasurementUnit(): string
    {
        return $this->measurement_unit;
    }

    /**
     * Устанавливает единицу измерения количества. Тег ФФД - 1197.
     *
     * @param string $measurement_unit Единица измерения количества
     * @return $this
     * @throws TooLongUnitException Слишком длинное название единицы измерения
     */
    public function setMeasurementUnit(string $measurement_unit): Item
    {
        $measurement_unit = trim($measurement_unit);
        if (mb_strlen($measurement_unit) > Constraints::MAX_LENGTH_MEASUREMENT_UNIT) {
            throw new TooLongUnitException($measurement_unit, Constraints::MAX_LENGTH_MEASUREMENT_UNIT);
        }
        $this->measurement_unit = $measurement_unit;
        return $this;
    }

    /**
     * Возвращает признак способа оплаты. Тег ФФД - 1214.
     *
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->payment_method;
    }

    /**
     * Устанавливает признак способа оплаты. Тег ФФД - 1214.
     *
     * @param string $payment_method Признак способа оплаты
     * @return $this
     * @todo Проверка допустимых значений
     */
    public function setPaymentMethod(string $payment_method): Item
    {
        $this->payment_method = trim($payment_method);
        return $this;
    }

    /**
     * Возвращает признак предмета расчёта. Тег ФФД - 1212.
     *
     * @return string
     */
    public function getPaymentObject(): string
    {
        return $this->payment_object;
    }

    /**
     * Устанавливает признак предмета расчёта. Тег ФФД - 1212.
     *
     * @param string $payment_object Признак предмета расчёта
     * @return $this
     * @todo Проверка допустимых значений
     */
    public function setPaymentObject(string $payment_object): Item
    {
        $this->payment_object = trim($payment_object);
        return $this;
    }

    /**
     * Возвращает ставку НДС
     *
     * @return Vat|null
     */
    public function getVat(): ?Vat
    {
        return $this->vat;
    }

    /**
     * Устанавливает ставку НДС
     *
     * @param string|null $vat_type Тип ставки НДС. Передать null, чтобы удалить ставку.
     * @return $this
     * @throws TooHighPriceException
     */
    public function setVatType(?string $vat_type): Item
    {
        if ($vat_type) {
            $this->vat
                ? $this->vat->setType($vat_type)
                : $this->vat = new Vat($vat_type);
        } else {
            $this->vat = null;
        }
        $this->calcSum();
        return $this;
    }

    /**
     * Возвращает дополнительный реквизит. Тег ФФД - 1191.
     *
     * @return string|null
     */
    public function getUserData(): ?string
    {
        return $this->user_data;
    }

    /**
     * Устанавливает дополнительный реквизит. Тег ФФД - 1191.
     *
     * @param string $user_data Дополнительный реквизит. Тег ФФД - 1191.
     * @return $this
     * @throws TooLongUserdataException Слишком длинный дополнительный реквизит
     */
    public function setUserData(string $user_data): self
    {
        $user_data = trim($user_data);
        if (mb_strlen($user_data) > Constraints::MAX_LENGTH_USER_DATA) {
            throw new TooLongUserdataException($user_data, Constraints::MAX_LENGTH_USER_DATA);
        }
        $this->user_data = $user_data;
        return $this;
    }

    /**
     * Возвращает стоимость. Тег ФФД - 1043.
     *
     * @return float
     */
    public function getSum(): float
    {
        return rubles($this->sum);
    }

    /**
     * Расчитывает стоимость и размер НДС на неё
     *
     * @return float
     * @throws TooHighPriceException Слишком большая сумма
     */
    public function calcSum(): float
    {
        $sum = $this->quantity * $this->price;
        if (rubles($sum) > 42949672.95) {
            throw new TooHighPriceException($sum, 42949672.95);
        }
        $this->sum = $sum;
        if ($this->vat) {
            $this->vat->setSum(rubles($sum));
        }
        return $this->getSum();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $json = [
            'name' => $this->getName(), // обязательно
            'price' => $this->getPrice(), // обязательно
            'quantity' => $this->getQuantity(), // обязательно
            'sum' => $this->getSum(), // обязательно
            'measurement_unit' => $this->getMeasurementUnit(),
            'payment_method' => $this->getPaymentMethod(),
            'payment_object' => $this->getPaymentObject()
            //TODO nomenclature_code
            //TODO agent_info
            //TODO supplier_info
            //TODO excise
            //TODO country_code
            //TODO declaration_number
        ];
        if ($this->getVat()) {
            $json['vat'] = $this->getVat()->jsonSerialize();
        }
        if ($this->getUserData()) {
            $json['user_data'] = $this->getUserData();
        }
        return $json;
    }
}
