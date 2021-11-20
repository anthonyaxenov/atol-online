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

use AtolOnline\Exceptions\EmptyMonitorDataException;
use AtolOnline\Exceptions\NotEnoughMonitorDataException;
use DateTime;
use Exception;

/**
 * Класс сущности ККТ, получаемой от монитора
 *
 * @property string|null serialNumber Заводской номер ККТ
 * @property string|null registrationNumber Регистрационный номер машины (РНМ)
 * @property string|null deviceNumber Номер автоматического устройства (внутренний идентификатор устройства)
 * @property DateTime|string|null fiscalizationDate Дата активации (фискализации) ФН с указанием таймзоны
 * @property DateTime|string|null fiscalStorageExpiration Дата замены ФН (Срок действия ФН), с указанием таймзоны
 * @property int|null signedDocuments Количество подписанных документов в ФН
 * @property float|null fiscalStoragePercentageUse Наполненость ФН в %
 * @property string|null fiscalStorageINN ИНН компании (указанный в ФН)
 * @property string|null fiscalStorageSerialNumber Заводской (серийный) номер ФН
 * @property string|null fiscalStoragePaymentAddress Адрес расчёта, указанный в ФН
 * @property string|null groupCode Код группы кассы
 * @property DateTime|string|null timestamp Время и дата формирования данных, UTC
 * @property bool|null isShiftOpened Признак открыта смена (true) или закрыта (false)
 * @property int|null shiftNumber Номер смены (или "Номер закрытой смены", когда смена закрыта)
 * @property int|null shiftReceipt Номер документа за смену (или "Кол-во чеков закрытой смены", когда смена закрыта)
 * @property int|null unsentDocs Количество неотправленных документов. Указывается, если значение отлично от 0.
 * @property DateTime|string|null firstUnsetDocTimestamp Дата первого неотправленного документа. Указывается, если
 * есть неотправленные документы.
 * @property int|null networkErrorCode Код ошибки сети
 * @see https://online.atol.ru/files/API_service_information.pdf Документация, стр 11
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
        6 => 'Разрыв соединения при приеме квитанции',
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
    public function __construct(protected \stdClass $data)
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
     * Возвращает объект с информацией о сетевой ошибке
     *
     * @return object
     */
    public function getNetworkError(): object
    {
        return (object)[
            'code' => $this->data->networkErrorCode,
            'text' => self::ERROR_CODES[$this->data->networkErrorCode],
        ];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->data;
    }
}
