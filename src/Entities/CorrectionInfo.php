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
use AtolOnline\Enums\CorrectionTypes;
use AtolOnline\Exceptions\{
    EmptyCorrectionNumberException,
    InvalidCorrectionDateException,
    InvalidEnumValueException
};
use DateTime;
use Exception;
use JetBrains\PhpStorm\{
    ArrayShape,
    Pure
};

/**
 * Класс, описывающий данные коррекции
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 35
 */
class CorrectionInfo extends Entity
{
    /**
     * @var string|null Тип коррекции (1173)
     */
    protected ?string $type = null;

    /**
     * @var string|null Дата документа основания для коррекции (1178)
     */
    protected ?string $date = null;

    /**
     * @var string|null Номер документа основания для коррекции (1179)
     */
    protected ?string $number = null;

    /**
     * Конструктор
     *
     * @param string $type Тип коррекции
     * @param string $date Дата документа
     * @param string $number Номер документа
     * @throws InvalidEnumValueException
     * @throws InvalidCorrectionDateException
     * @throws EmptyCorrectionNumberException
     */
    public function __construct(string $type, string $date, string $number)
    {
        $this->setType($type)->setDate($date)->setNumber($number);
    }

    /**
     * Возвращает тип коррекции
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Устанавливает тип коррекции
     *
     * @param string $type
     * @return $this
     * @throws InvalidEnumValueException
     */
    public function setType(string $type): self
    {
        $type = trim($type);
        CorrectionTypes::isValid($type) && $this->type = $type;
        return $this;
    }

    /**
     * Возвращает дату документа основания для коррекции
     *
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * Устанавливает дату документа основания для коррекции
     *
     * @param DateTime|string $date Строковая дата в формате d.m.Y либо объект DateTime с датой
     * @return $this
     * @throws InvalidCorrectionDateException
     */
    public function setDate(DateTime|string $date): self
    {
        try {
            if (is_string($date)) {
                $date = new DateTime(trim($date));
            }
            $this->date = $date->format(Constraints::CORRECTION_DATE_FORMAT);
        } catch (Exception $e) {
            throw new InvalidCorrectionDateException($date, $e->getMessage());
        }
        return $this;
    }

    /**
     * Возвращает установленный номер документа основания для коррекции
     *
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * Устанавливает номер документа основания для коррекции
     *
     * @param string $number
     * @return $this
     * @throws EmptyCorrectionNumberException
     */
    public function setNumber(string $number): self
    {
        $number = trim($number);
        empty($number) && throw new EmptyCorrectionNumberException();
        $this->number = $number;
        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    #[ArrayShape(['type' => 'string', 'base_date' => 'string', 'base_number' => 'string'])]
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->getType(),
            'base_date' => $this->getDate(),
            'base_number' => $this->getNumber(),
        ];
    }
}
