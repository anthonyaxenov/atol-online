<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Entities;

use AtolOnline\Exceptions\AtolCashierTooLongException;
use AtolOnline\Exceptions\AtolException;
use AtolOnline\Exceptions\AtolInvalidJsonException;

/**
 * Класс, описывающий документ
 *
 * @package AtolOnline\Entities
 */
class Document extends Entity
{
    /**
     * @var \AtolOnline\Entities\ItemArray Массив предметов расчёта
     */
    protected $items;
    
    /**
     * @var \AtolOnline\Entities\VatArray Массив ставок НДС
     */
    protected $vats;
    
    /**
     * @var \AtolOnline\Entities\PaymentArray Массив оплат
     */
    protected $payments;
    
    /**
     * @var \AtolOnline\Entities\Company Объект компании (продавца)
     */
    protected $company;
    
    /**
     * @var \AtolOnline\Entities\Client Объект клиента (покупателя)
     */
    protected $client;
    
    /**
     * @var int Итоговая сумма чека. Тег ФФД - 1020.
     */
    protected $total = 0;
    
    /**
     * @var string ФИО кассира. Тег ФФД - 1021.
     */
    protected $cashier;
    
    /**
     * @var \AtolOnline\Entities\CorrectionInfo Данные коррекции
     */
    protected $correction_info;
    
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
     * @throws \AtolOnline\Exceptions\AtolPriceTooHighException Слишком большая сумма
     * @throws \AtolOnline\Exceptions\AtolTooManyVatsException Слишком много ставок НДС
     */
    public function clearVats()
    {
        $this->setVats([]);
        return $this;
    }
    
    /**
     * Добавляет новую ставку НДС в массив ставок НДС
     *
     * @param \AtolOnline\Entities\Vat $vat Объект ставки НДС
     * @return $this
     * @throws \AtolOnline\Exceptions\AtolTooManyVatsException Слишком много ставок НДС
     */
    public function addVat(Vat $vat)
    {
        $this->vats->add($vat);
        return $this;
    }
    
    /**
     * Возвращает массив ставок НДС
     *
     * @return \AtolOnline\Entities\Vat[]
     */
    public function getVats(): array
    {
        return $this->vats->get();
    }
    
    /**
     * Устанавливает массив ставок НДС
     *
     * @param \AtolOnline\Entities\Vat[] $vats Массив ставок НДС
     * @return $this
     * @throws \AtolOnline\Exceptions\AtolTooManyVatsException Слишком много ставок НДС
     * @throws \Exception
     */
    public function setVats(array $vats)
    {
        $this->vats->set($vats);
        return $this;
    }
    
    /**
     * Добавляет новую оплату в массив оплат
     *
     * @param \AtolOnline\Entities\Payment $payment Объект оплаты
     * @return $this
     * @throws \Exception
     * @throws \AtolOnline\Exceptions\AtolTooManyPaymentsException Слишком много оплат
     */
    public function addPayment(Payment $payment)
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
     * @return \AtolOnline\Entities\Payment[]
     */
    public function getPayments(): array
    {
        return $this->payments->get();
    }
    
    /**
     * Устанавливает массив оплат
     *
     * @param \AtolOnline\Entities\Payment[] $payments Массив оплат
     * @return $this
     * @throws \AtolOnline\Exceptions\AtolTooManyPaymentsException Слишком много оплат
     */
    public function setPayments(array $payments)
    {
        $this->payments->set($payments);
        return $this;
    }
    
    /**
     * Добавляет новый предмет расчёта в массив предметов расчёта
     *
     * @param \AtolOnline\Entities\Item $item Объект предмета расчёта
     * @return $this
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException Слишком много предметов расчёта
     */
    public function addItem(Item $item)
    {
        $this->items->add($item);
        return $this;
    }
    
    /**
     * Возвращает массив предметов расчёта
     *
     * @return \AtolOnline\Entities\Item[]
     */
    public function getItems(): array
    {
        return $this->items->get();
    }
    
    /**
     * Устанавливает массив предметов расчёта
     *
     * @param \AtolOnline\Entities\Item[] $items Массив предметов расчёта
     * @return $this
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException Слишком много предметов расчёта
     */
    public function setItems(array $items)
    {
        $this->items->set($items);
        return $this;
    }
    
    /**
     * Возвращает заданного клиента (покупателя)
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
    
    /**
     * Устанавливает клиента (покупателя)
     *
     * @param Client|null $client
     * @return $this
     */
    public function setClient(?Client $client)
    {
        $this->client = $client;
        return $this;
    }
    
    /**
     * Возвращает заданную компанию (продавца)
     *
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }
    
    /**
     * Устанавливает компанию (продавца)
     *
     * @param Company|null $company
     * @return $this
     */
    public function setCompany(?Company $company)
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
     * @throws \AtolOnline\Exceptions\AtolCashierTooLongException
     */
    public function setCashier(?string $cashier)
    {
        $cashier = trim($cashier);
        if ((function_exists('mb_strlen') ? mb_strlen($cashier) : strlen($cashier)) > 64) {
            throw new AtolCashierTooLongException($cashier, 64);
        }
        $this->cashier = $cashier;
        return $this;
    }
    
    /**
     * Возвращает данные коррекции
     *
     * @return \AtolOnline\Entities\CorrectionInfo|null
     */
    public function getCorrectionInfo(): ?CorrectionInfo
    {
        return $this->correction_info;
    }
    
    /**
     * Устанавливает данные коррекции
     *
     * @param \AtolOnline\Entities\CorrectionInfo|null $correction_info
     * @return $this
     */
    public function setCorrectionInfo(?CorrectionInfo $correction_info)
    {
        $this->correction_info = $correction_info;
        return $this;
    }
    
    /**
     * Пересчитывает, сохраняет и возвращает итоговую сумму чека по всем позициям (включая НДС). Тег ФФД - 1020.
     *
     * @return float
     * @throws \Exception
     */
    public function calcTotal()
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
     * @return \AtolOnline\Entities\Document
     * @throws \AtolOnline\Exceptions\AtolEmailTooLongException
     * @throws \AtolOnline\Exceptions\AtolEmailValidateException
     * @throws \AtolOnline\Exceptions\AtolException
     * @throws \AtolOnline\Exceptions\AtolInnWrongLengthException
     * @throws \AtolOnline\Exceptions\AtolInvalidJsonException
     * @throws \AtolOnline\Exceptions\AtolNameTooLongException
     * @throws \AtolOnline\Exceptions\AtolPaymentAddressTooLongException
     * @throws \AtolOnline\Exceptions\AtolPhoneTooLongException
     * @throws \AtolOnline\Exceptions\AtolPriceTooHighException
     * @throws \AtolOnline\Exceptions\AtolTooManyException
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException
     * @throws \AtolOnline\Exceptions\AtolTooManyPaymentsException
     * @throws \AtolOnline\Exceptions\AtolUnitTooLongException
     * @throws \AtolOnline\Exceptions\AtolUserdataTooLongException
     */
    public static function fromRaw(string $json)
    {
        $object = json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new AtolInvalidJsonException();
        }
        $doc = new self();
        if ($object->company) {
            $doc->setCompany(new Company(
                $object->company->sno ?? null,
                $object->company->inn ?? null,
                $object->company->payment_address ?? null,
                $object->company->email ?? null
            ));
        }
        if ($object->client) {
            $doc->setClient(new Client(
                $object->client->name ?? null,
                $object->client->phone ?? null,
                $object->client->email ?? null,
                $object->client->inn ?? null
            ));
        }
        if ($object->items) {
            foreach ($object->items as $obj_item) {
                $item = new Item(
                    $obj_item->name ?? null,
                    $obj_item->price ?? null,
                    $obj_item->quantity ?? null,
                    $obj_item->measurement_unit ?? null,
                    $obj_item->vat->type ?? null,
                    $obj_item->payment_object ?? null,
                    $obj_item->payment_method ?? null
                );
                if (!empty($obj_item->user_data)) {
                    $item->setUserData($obj_item->user_data ?? null);
                }
                $doc->addItem($item);
            }
        }
        if ($object->payments) {
            foreach ($object->payments as $obj_payment) {
                $doc->payments->add(new Payment(
                    $obj_payment->type,
                    $obj_payment->sum
                ));
            }
        }
        //if ($object->vats) {
        //    foreach ($object->vats as $obj_vat) {
        //        $doc->vats->add(new Vat(
        //            $obj_vat->type
        //        ));
        //    }
        //}
        if ($object->total != $doc->calcTotal()) {
            throw new AtolException('Real total sum not equals to provided in JSON one');
        }
        return $doc;
    }
    
    /**
     * Возвращает массив для кодирования в json
     *
     * @throws \Exception
     */
    public function jsonSerialize()
    {
        $json = [
            'company' => $this->getCompany()->jsonSerialize(), // обязательно
            'payments' => $this->payments->jsonSerialize(), // обязательно
            'cashier' => $this->getCashier() ?? '',
        ];
        if ($this->getCorrectionInfo()) {
            $json['correction_info'] = $this->getCorrectionInfo()->jsonSerialize(); // обязательно для коррекционных
        } else {
            $json['client'] = $this->getClient()->jsonSerialize(); // обязательно для некоррекционных
            $json['items'] = $this->items->jsonSerialize(); // обязательно для некоррекционных
            $json['total'] = $this->calcTotal(); // обязательно для некоррекционных
        }
        if ($this->getVats()) {
            $json['vats'] = $this->vats->jsonSerialize();
        }
        return $json;
    }
}