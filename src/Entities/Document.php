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
use AtolOnline\Exceptions\AtolException;
use AtolOnline\Exceptions\BasicTooManyException;
use AtolOnline\Exceptions\InvalidEmailException;
use AtolOnline\Exceptions\InvalidInnLengthException;
use AtolOnline\Exceptions\InvalidJsonException;
use AtolOnline\Exceptions\TooHighPriceException;
use AtolOnline\Exceptions\TooLongCashierException;
use AtolOnline\Exceptions\TooLongEmailException;
use AtolOnline\Exceptions\TooLongNameException;
use AtolOnline\Exceptions\TooLongPaymentAddressException;
use AtolOnline\Exceptions\TooLongPhoneException;
use AtolOnline\Exceptions\TooLongUnitException;
use AtolOnline\Exceptions\TooLongUserdataException;
use AtolOnline\Exceptions\TooManyItemsException;
use AtolOnline\Exceptions\TooManyPaymentsException;
use AtolOnline\Exceptions\TooManyVatsException;
use Exception;

/**
 * Класс, описывающий документ
 *
 * @package AtolOnline\Entities
 */
class Document extends Entity
{
    /**
     * @var ItemArray Массив предметов расчёта
     */
    protected ItemArray $items;

    /**
     * @var VatArray Массив ставок НДС
     */
    protected VatArray $vats;

    /**
     * @var PaymentArray Массив оплат
     */
    protected PaymentArray $payments;

    /**
     * @var Company Объект компании (продавца)
     */
    protected Company $company;

    /**
     * @var Client Объект клиента (покупателя)
     */
    protected Client $client;

    /**
     * @var float Итоговая сумма чека. Тег ФФД - 1020.
     */
    protected float $total = 0;
    
    /**
     * @var string ФИО кассира. Тег ФФД - 1021.
     */
    protected string $cashier;

    /**
     * @var CorrectionInfo Данные коррекции
     */
    protected CorrectionInfo $correction_info;
    
    /**
     * Document constructor.
     */
    public function __construct()
    {
        $this->vats = new VatArray();
        $this->payments = new PaymentArray();
        $this->items = new ItemArray();
    }

    /**
     * Удаляет все налоги из документа и предметов расчёта
     *
     * @return $this
     * @throws TooManyVatsException Слишком много ставок НДС
     */
    public function clearVats(): Document
    {
        $this->setVats([]);
        return $this;
    }

    /**
     * Добавляет новую ставку НДС в массив ставок НДС
     *
     * @param Vat $vat Объект ставки НДС
     * @return $this
     * @throws TooManyVatsException Слишком много ставок НДС
     */
    public function addVat(Vat $vat): Document
    {
        $this->vats->add($vat);
        return $this;
    }

    /**
     * Возвращает массив ставок НДС
     *
     * @return Vat[]
     */
    public function getVats(): array
    {
        return $this->vats->get();
    }

    /**
     * Устанавливает массив ставок НДС
     *
     * @param Vat[] $vats Массив ставок НДС
     * @return $this
     * @throws TooManyVatsException Слишком много ставок НДС
     * @throws Exception
     */
    public function setVats(array $vats): Document
    {
        $this->vats->set($vats);
        return $this;
    }

    /**
     * Добавляет новую оплату в массив оплат
     *
     * @param Payment $payment Объект оплаты
     * @return $this
     * @throws Exception
     * @throws TooManyPaymentsException Слишком много оплат
     */
    public function addPayment(Payment $payment): Document
    {
        if (count($this->getPayments()) == 0 && !$payment->getSum()) {
            $payment->setSum($this->calcTotal());
        }
        $this->payments->add($payment);
        return $this;
    }

    /**
     * Возвращает массив оплат
     *
     * @return Payment[]
     */
    public function getPayments(): array
    {
        return $this->payments->get();
    }

    /**
     * Устанавливает массив оплат
     *
     * @param Payment[] $payments Массив оплат
     * @return $this
     * @throws TooManyPaymentsException Слишком много оплат
     */
    public function setPayments(array $payments): Document
    {
        $this->payments->set($payments);
        return $this;
    }

    /**
     * Добавляет новый предмет расчёта в массив предметов расчёта
     *
     * @param Item $item Объект предмета расчёта
     * @return $this
     * @throws TooManyItemsException Слишком много предметов расчёта
     */
    public function addItem(Item $item): Document
    {
        $this->items->add($item);
        return $this;
    }

    /**
     * Возвращает массив предметов расчёта
     *
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items->get();
    }

    /**
     * Устанавливает массив предметов расчёта
     *
     * @param Item[] $items Массив предметов расчёта
     * @return $this
     * @throws TooManyItemsException Слишком много предметов расчёта
     */
    public function setItems(array $items): Document
    {
        $this->items->set($items);
        return $this;
    }
    
    /**
     * Возвращает заданного клиента (покупателя)
     *
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }
    
    /**
     * Устанавливает клиента (покупателя)
     *
     * @param Client|null $client
     * @return $this
     */
    public function setClient(?Client $client): Document
    {
        $this->client = $client;
        return $this;
    }
    
    /**
     * Возвращает заданную компанию (продавца)
     *
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }
    
    /**
     * Устанавливает компанию (продавца)
     *
     * @param Company|null $company
     * @return $this
     */
    public function setCompany(?Company $company): Document
    {
        $this->company = $company;
        return $this;
    }
    
    /**
     * Возвращает ФИО кассира. Тег ФФД - 1021.
     *
     * @return string|null
     */
    public function getCashier(): ?string
    {
        return $this->cashier;
    }

    /**
     * Устанавливает ФИО кассира. Тег ФФД - 1021.
     *
     * @param string|null $cashier
     * @return $this
     * @throws TooLongCashierException
     */
    public function setCashier(?string $cashier): Document
    {
        if ($cashier !== null) {
            $cashier = trim($cashier);
            if (mb_strlen($cashier) > Constraints::MAX_LENGTH_CASHIER_NAME) {
                throw new TooLongCashierException($cashier, Constraints::MAX_LENGTH_CASHIER_NAME);
            }
        }
        $this->cashier = $cashier;
        return $this;
    }

    /**
     * Возвращает данные коррекции
     *
     * @return CorrectionInfo|null
     */
    public function getCorrectionInfo(): ?CorrectionInfo
    {
        return $this->correction_info;
    }

    /**
     * Устанавливает данные коррекции
     *
     * @param CorrectionInfo|null $correction_info
     * @return $this
     */
    public function setCorrectionInfo(?CorrectionInfo $correction_info): Document
    {
        $this->correction_info = $correction_info;
        return $this;
    }

    /**
     * Пересчитывает, сохраняет и возвращает итоговую сумму чека по всем позициям (включая НДС). Тег ФФД - 1020.
     *
     * @return float
     * @throws Exception
     */
    public function calcTotal(): float
    {
        $sum = 0;
        $this->clearVats();
        foreach ($this->items->get() as $item) {
            $sum += $item->calcSum();
            $this->addVat(new Vat($item->getVat()->getType(), $item->getSum()));
        }
        return $this->total = round($sum, 2);
    }
    
    /**
     * Возвращает итоговую сумму чека. Тег ФФД - 1020.
     *
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * Собирает объект документа из сырой json-строки
     *
     * @param string $json
     * @return Document
     * @throws TooLongEmailException
     * @throws InvalidEmailException
     * @throws AtolException
     * @throws InvalidInnLengthException
     * @throws InvalidJsonException
     * @throws TooLongNameException
     * @throws TooLongPaymentAddressException
     * @throws TooLongPhoneException
     * @throws TooHighPriceException
     * @throws BasicTooManyException
     * @throws TooManyItemsException
     * @throws TooManyPaymentsException
     * @throws TooLongUnitException
     * @throws TooLongUserdataException
     * @throws Exception
     */
    public static function fromRaw(string $json): Document
    {
        $array = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException();
        }
        $doc = new self();
        if (isset($array['company'])) {
            $doc->setCompany(new Company(
                $array['company']['sno'] ?? null,
                $array['company']['inn'] ?? null,
                $array['company']['payment_address'] ?? null,
                $array['company']['email'] ?? null
            ));
        }
        if (isset($array['client'])) {
            $doc->setClient(new Client(
                $array['client']['name'] ?? null,
                $array['client']['phone'] ?? null,
                $array['client']['email'] ?? null,
                $array['client']['inn'] ?? null
            ));
        }
        if (isset($array['correction_info'])) {
            $doc->setCorrectionInfo(new CorrectionInfo(
                $array['correction_info']['type'] ?? null,
                $array['correction_info']['base_date'] ?? null,
                $array['correction_info']['base_number'] ?? null,
                $array['correction_info']['base_name'] ?? null,
            ));
        }
        if (isset($array['items'])) {
            foreach ($array['items'] as $ar_item) {
                $item = new Item(
                    $ar_item['name'] ?? null,
                    $ar_item['price'] ?? null,
                    $ar_item['quantity'] ?? null,
                    $ar_item['measurement_unit'] ?? null,
                    $ar_item['vat']['type'] ?? null,
                    $ar_item['payment_object'] ?? null,
                    $ar_item['payment_method'] ?? null
                );
                if (!empty($ar_item['user_data'])) {
                    $item->setUserData($ar_item['user_data'] ?? null);
                }
                $doc->addItem($item);
            }
        }
        if (isset($array['payments'])) {
            foreach ($array['payments'] as $ar_payment) {
                $payment = new Payment();
                if (isset($ar_payment['type'])) {
                    $payment->setType($ar_payment['type']);
                }
                if (isset($ar_payment['sum'])) {
                    $payment->setSum($ar_payment['sum']);
                }
                $doc->payments->add($payment);
            }
        }
        if (isset($array['vats'])) {
            foreach ($array['vats'] as $vat_payment) {
                $vat = new Vat();
                if (isset($vat_payment['type'])) {
                    $vat->setType($vat_payment['type']);
                }
                if (isset($vat_payment['sum'])) {
                    $vat->setSum($vat_payment['sum']);
                }
                $doc->vats->add($vat);
            }
        }
        if (isset($array['total']) && $array['total'] != $doc->calcTotal()) {
            throw new AtolException('Real total sum not equals to provided in JSON one');
        }
        return $doc;
    }

    /**
     * Возвращает массив для кодирования в json
     *
     * @throws Exception
     */
    public function jsonSerialize(): array
    {
        if ($this->getCompany()) {
            $json['company'] = $this->getCompany()->jsonSerialize(); // обязательно
        }
        if ($this->getPayments()) {
            $json['payments'] = $this->payments->jsonSerialize(); // обязательно
        }
        if ($this->getCashier()) {
            $json['cashier'] = $this->getCashier();
        }
        if ($this->getCorrectionInfo()) {
            $json['correction_info'] = $this->getCorrectionInfo()->jsonSerialize(); // обязательно для коррекционных
        } else {
            if ($this->getClient()) {
                $json['client'] = $this->getClient()->jsonSerialize(); // обязательно для некоррекционных
            }
            if ($this->getItems()) {
                $json['items'] = $this->items->jsonSerialize(); // обязательно для некоррекционных
            }
            $json['total'] = $this->calcTotal(); // обязательно для некоррекционных
        }
        if ($this->getVats()) {
            $json['vats'] = $this->vats->jsonSerialize();
        }
        return $json;
    }
}
