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

use AtolOnline\Exceptions\{
    EmptyMonitorDataException,
    NotEnoughMonitorDataException};
use DateTime;
use Exception;

/**
 * Класс сущности ККТ, получаемой от монитора
 *
 * @see https://online.atol.ru/files/API_service_information.pdf Документация, стр 11
 * @property-read string|null serialNumber Заводской номер ККТ
 * @property-read string|null registrationNumber Регистрационный номер машины (РНМ)
 * @property-read string|null deviceNumber Номер автоматического устройства (внутренний идентификатор устройства)
 * @property-read DateTime|string|null fiscalizationDate Дата активации (фискализации) ФН с указанием таймзоны
 * @property-read DateTime|string|null fiscalStorageExpiration Дата замены ФН (Срок действия ФН), с указанием таймзоны
 * @property-read int|null signedDocuments Количество подписанных документов в ФН
 * @property-read float|null fiscalStoragePercentageUse Наполненость ФН в %
 * @property-read string|null fiscalStorageINN ИНН компании (указанный в ФН)
 * @property-read string|null fiscalStorageSerialNumber Заводской (серийный) номер ФН
 * @property-read string|null fiscalStoragePaymentAddress Адрес расчёта, указанный в ФН
 * @property-read string|null groupCode Код группы кассы
 * @property-read DateTime|string|null timestamp Время и дата формирования данных, UTC
 * @property-read bool|null isShiftOpened Признак открыта смена (true) или закрыта (false)
 * @property-read int|null shiftNumber Номер смены (или "Номер закрытой смены", когда смена закрыта)
 * @property-read int|null shiftReceipt Номер документа за смену (или "Кол-во чеков закрытой смены", когда смена
 * закрыта)
 * @property-read int|null unsentDocs Количество неотправленных документов. Указывается, если значение отлично от 0.
 * @property-read DateTime|string|null firstUnsetDocTimestamp Дата первого неотправленного документа. Указывается, если
 * есть неотправленные документы.
 * @property-read int|null networkErrorCode Код ошибки сети
 */
final class Kkt extends Entity
{
    /**
     * Сопоставление кодов сетевых ошибок ККТ с их описаниями
     */
    public const ERROR_CODES = [
        0 => 'Нет ошибок',
        1 => 'Отсутствует физический канал связи',
        2 => 'Ошибка сетевых настроек или нет соединения с сервером ОФД',
        3 => 'Разрыв соединения при передаче документа на сервер',
        4 => 'Некорректный заголовок сессионного пакета',
        5 => 'Превышен таймаут ожидания квитанции',
        6 => 'Разрыв соединения при приёме квитанции',
        7 => 'Превышен таймаут передачи документа на сервер',
        8 => 'ОФД-процесс не иницилизирован',
    ];

    /**
     * @var string[] Список обязательных атрибутов
     */
    private array $properties = [
        'serialNumber',
        'registrationNumber',
        'deviceNumber',
        'fiscalizationDate',
        'fiscalStorageExpiration',
        'signedDocuments',
        'fiscalStoragePercentageUse',
        'fiscalStorageINN',
        'fiscalStorageSerialNumber',
        'fiscalStoragePaymentAddress',
        'groupCode',
        'timestamp',
        'isShiftOpened',
        'shiftNumber',
        'shiftReceipt',
        //'unsentDocs',
        //'firstUnsetDocTimestamp',
        'networkErrorCode',
    ];

    /**
     * @var string[] Массив атрибутов, которые кастуются к DateTime
     */
    private array $timestamps = [
        'fiscalizationDate',
        'fiscalStorageExpiration',
        'firstUnsetDocTimestamp',
        'timestamp',
    ];

    /**
     * Конструктор
     *
     * @throws EmptyMonitorDataException
     * @throws NotEnoughMonitorDataException
     */
    public function __construct(protected object $data)
    {
        if (empty((array)$data)) {
            throw new EmptyMonitorDataException();
        }
        $diff = array_diff($this->properties, array_keys((array)$data));
        if (count($diff) !== 0) {
            throw new NotEnoughMonitorDataException($diff);
        }
    }

    /**
     * Эмулирует обращение к атрибутам
     *
     * @param string $name
     * @return null
     * @throws Exception
     */
    public function __get(string $name)
    {
        if (empty($this->data?->$name)) {
            return null;
        }
        if (in_array($name, $this->timestamps)) {
            return new DateTime($this->data->$name);
        }
        return $this->data->$name;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return (array)$this->data;
    }
}
