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

use AtolOnline\Constraints;
use AtolOnline\Enums\{
    PaymentMethod,
    PaymentObject,
    VatType};
use AtolOnline\Exceptions\{
    EmptyItemNameException,
    InvalidDeclarationNumberException,
    InvalidOKSMCodeException,
    NegativeItemExciseException,
    NegativeItemPriceException,
    NegativeItemQuantityException,
    TooHighItemPriceException,
    TooHighItemQuantityException,
    TooHighItemSumException,
    TooLongItemCodeException,
    TooLongItemNameException,
    TooLongMeasurementUnitException,
    TooLongUserdataException,
    TooManyException};

/**
 * Предмет расчёта (товар, услуга)
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 21-30
 */
final class Item extends Entity
{
    /**
     * @var string|null Единица измерения (1197)
     */
    protected ?string $measurement_unit = null;

    /**
     * @var string|null Код товара (1162)
     */
    protected ?string $code = null;

    /**
     * @var string|null Код товара (1162) в форматированной шестнадцатиричной форме
     */
    protected ?string $codeHex = null;

    /**
     * @var PaymentMethod|null Признак способа расчёта (1214)
     */
    protected ?PaymentMethod $paymentMethod = null;

    /**
     * @var PaymentObject|null Признак предмета расчёта (1212)
     */
    protected ?PaymentObject $paymentObject = null;

    /**
     * @var string|null Номер таможенной декларации (1321)
     */
    protected ?string $declarationNumber = null;

    /**
     * @var Vat|null Ставка НДС
     */
    protected ?Vat $vat = null;

    /**
     * @var AgentInfo|null Атрибуты агента
     */
    protected ?AgentInfo $agentInfo = null;

    /**
     * @var Supplier|null Атрибуты поставшика
     */
    protected ?Supplier $supplier = null;

    /**
     * @var string|null Дополнительный реквизит (1191)
     */
    protected ?string $userData = null;

    /**
     * @var float|null Сумма акциза, включенная в стоимость (1229)
     */
    protected ?float $excise = null;

    /**
     * @var string|null Цифровой код страны происхождения товара (1230)
     */
    protected ?string $countryCode = null;

    /**
     * Конструктор
     *
     * @param string|null $name Наименование (1030)
     * @param float|null $price Цена в рублях (с учётом скидок и наценок) (1079)
     * @param float|null $quantity Количество/вес (1023)
     * @throws TooLongItemNameException
     * @throws TooHighItemPriceException
     * @throws TooManyException
     * @throws NegativeItemPriceException
     * @throws EmptyItemNameException
     * @throws NegativeItemQuantityException
     */
    public function __construct(
        protected ?string $name = null,
        protected ?float $price = null,
        protected ?float $quantity = null,
    ) {
        !is_null($name) && $this->setName($name);
        !is_null($price) && $this->setPrice($price);
        !is_null($quantity) && $this->setQuantity($quantity);
    }

    /**
     * Возвращает наименование
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Устаналивает наименование
     *
     * @param string $name Наименование
     * @return $this
     * @throws TooLongItemNameException
     * @throws EmptyItemNameException
     */
    public function setName(string $name): self
    {
        if (mb_strlen($name = trim($name)) > Constraints::MAX_LENGTH_ITEM_NAME) {
            throw new TooLongItemNameException($name);
        }
        empty($name) && throw new EmptyItemNameException();
        $this->name = $name;
        return $this;
    }

    /**
     * Возвращает цену в рублях
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Устанавливает цену в рублях
     *
     * @param float $price
     * @return $this
     * @throws NegativeItemPriceException
     * @throws TooHighItemPriceException
     * @throws TooHighItemSumException
     */
    public function setPrice(float $price): self
    {
        $price = round($price, 2);
        $price > Constraints::MAX_COUNT_ITEM_PRICE && throw new TooHighItemPriceException($this->getName(), $price);
        $price < 0 && throw new NegativeItemPriceException($this->getName(), $price);
        $this->price = $price;
        $this->getVat()?->setSum($this->getSum());
        return $this;
    }

    /**
     * Возвращает количество
     *
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * Устанавливает количество
     *
     * @param float $quantity Количество
     * @return $this
     * @throws TooHighItemQuantityException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemSumException
     */
    public function setQuantity(float $quantity): self
    {
        $quantity = round($quantity, 3);
        if ($quantity > Constraints::MAX_COUNT_ITEM_QUANTITY) {
            throw new TooHighItemQuantityException($this->getName(), $quantity);
        }
        $quantity < 0 && throw new NegativeItemQuantityException($this->getName(), $quantity);
        $this->quantity = $quantity;
        $this->getVat()?->setSum($this->getSum());
        return $this;
    }

    /**
     * Возвращает стоимость (цена * количество + акциз)
     *
     * @return float
     * @throws TooHighItemSumException
     */
    public function getSum(): float
    {
        $sum = $this->getPrice() * $this->getQuantity() + (float)$this->getExcise();
        if ($sum > Constraints::MAX_COUNT_ITEM_SUM) {
            throw new TooHighItemSumException($this->getName(), $sum);
        }
        return $sum;
    }

    /**
     * Возвращает заданную единицу измерения количества
     *
     * @return string|null
     */
    public function getMeasurementUnit(): ?string
    {
        return $this->measurement_unit;
    }

    /**
     * Устанавливает единицу измерения количества
     *
     * @param string|null $measurement_unit
     * @return $this
     * @throws TooLongMeasurementUnitException
     */
    public function setMeasurementUnit(?string $measurement_unit): self
    {
        $measurement_unit = trim((string)$measurement_unit);
        if (mb_strlen($measurement_unit) > Constraints::MAX_LENGTH_MEASUREMENT_UNIT) {
            throw new TooLongMeasurementUnitException($measurement_unit);
        }
        $this->measurement_unit = $measurement_unit ?: null;
        return $this;
    }

    /**
     * Возвращает установленный код товара
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Возвращает шестнадцатиричное представление кода товара
     *
     * @return string|null
     */
    public function getCodeHex(): ?string
    {
        return $this->codeHex;
    }

    /**
     * Устанавливает код товара
     *
     * @param string|null $code
     * @return Item
     * @throws TooLongItemCodeException
     */
    public function setCode(?string $code): self
    {
        $hex_string = null;
        $code = trim((string)$code);
        if (mb_strlen($code) > Constraints::MAX_LENGTH_ITEM_CODE) {
            throw new TooLongItemCodeException($this->getName(), $code);
        }
        if (!empty($code)) {
            $hex = bin2hex($code);
            $hex_string = trim(preg_replace('/([\dA-Fa-f]{2})/', '$1 ', $hex));
        }
        $this->code = $code ?: null;
        $this->codeHex = $hex_string ?: null;
        return $this;
    }

    /**
     * Возвращает признак способа оплаты
     *
     * @return PaymentMethod|null
     */
    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    /**
     * Устанавливает признак способа оплаты
     *
     * @param PaymentMethod|null $paymentMethod Признак способа оплаты
     * @return $this
     */
    public function setPaymentMethod(?PaymentMethod $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * Возвращает признак предмета расчёта
     *
     * @return PaymentObject|null
     */
    public function getPaymentObject(): ?PaymentObject
    {
        return $this->paymentObject;
    }

    /**
     * Устанавливает признак предмета расчёта
     *
     * @param PaymentObject|null $paymentObject Признак предмета расчёта
     * @return $this
     */
    public function setPaymentObject(?PaymentObject $paymentObject): self
    {
        $this->paymentObject = $paymentObject;
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
     * @param Vat | VatType | null $vat Объект ставки, одно из значений VatTypes или null для удаления ставки
     * @return $this
     * @throws TooHighItemSumException
     */
    public function setVat(Vat | VatType | null $vat): self
    {
        if (is_null($vat)) {
            $this->vat = null;
        } elseif ($vat instanceof Vat) {
            $vat->setSum($this->getSum());
            $this->vat = $vat;
        } else {
            $this->vat = new Vat($vat, $this->getSum());
        }
        return $this;
    }

    /**
     * Возвращает установленный объект атрибутов агента
     *
     * @return AgentInfo|null
     */
    public function getAgentInfo(): ?AgentInfo
    {
        return $this->agentInfo;
    }

    /**
     * Устанавливает атрибуты агента
     *
     * @param AgentInfo|null $agentInfo
     * @return Item
     */
    public function setAgentInfo(?AgentInfo $agentInfo): self
    {
        $this->agentInfo = $agentInfo;
        return $this;
    }

    /**
     * Возвращает установленного поставщика
     *
     * @return Supplier|null
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    /**
     * Устанавливает поставщика
     *
     * @param Supplier|null $supplier
     * @return Item
     */
    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * Возвращает дополнительный реквизит
     *
     * @return string|null
     */
    public function getUserData(): ?string
    {
        return $this->userData;
    }

    /**
     * Устанавливает дополнительный реквизит
     *
     * @param string|null $userData Дополнительный реквизит
     * @return $this
     * @throws TooLongUserdataException
     */
    public function setUserData(?string $userData): self
    {
        $userData = trim((string)$userData);
        if (mb_strlen($userData) > Constraints::MAX_LENGTH_USER_DATA) {
            throw new TooLongUserdataException($userData);
        }
        $this->userData = $userData ?: null;
        return $this;
    }

    /**
     * Возвращает установленную сумму акциза
     *
     * @return float|null
     */
    public function getExcise(): ?float
    {
        return $this->excise;
    }

    /**
     * Устанавливает сумму акциза
     *
     * @param float|null $excise
     * @return Item
     * @throws NegativeItemExciseException
     * @throws TooHighItemSumException
     */
    public function setExcise(?float $excise): self
    {
        if ($excise < 0) {
            throw new NegativeItemExciseException($this->getName(), $excise);
        }
        $this->excise = $excise;
        $this->getVat()?->setSum($this->getSum());
        return $this;
    }

    /**
     * Возвращает установленный код страны происхождения товара
     *
     * @return string|null
     * @see https://ru.wikipedia.org/wiki/Общероссийский_классификатор_стран_мира
     * @see https://classifikators.ru/oksm
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * Устанавливает код страны происхождения товара
     *
     * @param string|null $countryCode
     * @return Item
     * @throws InvalidOKSMCodeException
     * @see https://classifikators.ru/oksm
     * @see https://ru.wikipedia.org/wiki/Общероссийский_классификатор_стран_мира
     */
    public function setCountryCode(?string $countryCode): self
    {
        $countryCode = trim((string)$countryCode);
        if (preg_match(Constraints::PATTERN_OKSM_CODE, $countryCode) != 1) {
            throw new InvalidOKSMCodeException($countryCode);
        }
        $this->countryCode = $countryCode ?: null;
        return $this;
    }

    /**
     * Возвращает установленный код таможенной декларации
     *
     * @return string|null
     */
    public function getDeclarationNumber(): ?string
    {
        return $this->declarationNumber;
    }

    /**
     * Устанавливает код таможенной декларации
     *
     * @param string|null $declarationNumber
     * @return Item
     * @throws InvalidDeclarationNumberException
     */
    public function setDeclarationNumber(?string $declarationNumber): self
    {
        if (is_string($declarationNumber)) {
            $declarationNumber = trim($declarationNumber);
            $is_short = mb_strlen($declarationNumber) < Constraints::MIN_LENGTH_DECLARATION_NUMBER;
            $is_long = mb_strlen($declarationNumber) > Constraints::MAX_LENGTH_DECLARATION_NUMBER;
            if ($is_short || $is_long) {
                throw new InvalidDeclarationNumberException($declarationNumber);
            }
        }
        $this->declarationNumber = $declarationNumber;
        return $this;
    }

    /**
     * @inheritDoc
     * @throws TooHighItemSumException
     */
    public function jsonSerialize(): array
    {
        $json = [
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity(),
            'sum' => $this->getSum(),
        ];
        !is_null($this->getMeasurementUnit()) && $json['measurement_unit'] = $this->getMeasurementUnit();
        !is_null($this->getCodeHex()) && $json['nomenclature_code'] = $this->getCodeHex();
        !is_null($this->getPaymentMethod()) && $json['payment_method'] = $this->getPaymentMethod();
        !is_null($this->getPaymentObject()) && $json['payment_object'] = $this->getPaymentObject();
        !is_null($this->getDeclarationNumber()) && $json['declaration_number'] = $this->getDeclarationNumber();
        $this->getVat()?->jsonSerialize() && $json['vat'] = $this->getVat()->jsonSerialize();
        $this->getAgentInfo()?->jsonSerialize() && $json['agent_info'] = $this->getAgentInfo()->jsonSerialize();
        $this->getSupplier()?->jsonSerialize() && $json['supplier_info'] = $this->getSupplier()->jsonSerialize();
        !is_null($this->getUserData()) && $json['user_data'] = $this->getUserData();
        !is_null($this->getExcise()) && $json['excise'] = $this->getExcise();
        !is_null($this->getCountryCode()) && $json['country_code'] = $this->getCountryCode();
        return $json;
    }
}
