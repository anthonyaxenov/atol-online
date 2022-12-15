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
use AtolOnline\Enums\CorrectionType;
use AtolOnline\Exceptions\{
    EmptyCorrectionNumberException,
    InvalidCorrectionDateException,};
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use JetBrains\PhpStorm\{
    ArrayShape};

/**
 * Класс, описывающий данные коррекции
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 35
 */
final class CorrectionInfo extends Entity
{
    /**
     * @var DateTimeImmutable Дата документа основания для коррекции (1178)
     */
    protected DateTimeImmutable $date;

    /**
     * Конструктор
     *
     * @param CorrectionType $type Тип коррекции (1173)
     * @param DateTimeInterface|string $date Дата документа основания для коррекции (1178)
     * @param string $number Номер документа основания для коррекции (1179)
     * @throws InvalidCorrectionDateException
     * @throws EmptyCorrectionNumberException
     */
    public function __construct(
        protected CorrectionType $type,
        DateTimeInterface | string $date,
        protected string $number,
    ) {
        $this->setType($type)
            ->setDate($date)
            ->setNumber($number);
    }

    /**
     * Возвращает тип коррекции
     *
     * @return CorrectionType|null
     */
    public function getType(): ?CorrectionType
    {
        return $this->type;
    }

    /**
     * Устанавливает тип коррекции
     *
     * @param CorrectionType $type
     * @return $this
     */
    public function setType(CorrectionType $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Возвращает дату документа основания для коррекции
     *
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * Устанавливает дату документа основания для коррекции
     *
     * @param DateTimeInterface|string $date Строковая дата в формате d.m.Y либо объект DateTime с датой
     * @return $this
     * @throws InvalidCorrectionDateException
     */
    public function setDate(DateTimeInterface | string $date): self
    {
        try {
            if (is_string($date)) {
                $this->date = new DateTimeImmutable(trim($date));
            } elseif ($date instanceof DateTime) {
                $this->date = DateTimeImmutable::createFromMutable($date);
            } elseif ($date instanceof DateTimeImmutable) {
                $this->date = $date;
            }
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
    #[ArrayShape([
        'type' => 'string',
        'base_date' => 'string',
        'base_number' => 'string',
    ])]
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->getType(),
            'base_date' => $this->getDate()->format(Constraints::CORRECTION_DATE_FORMAT),
            'base_number' => $this->getNumber(),
        ];
    }
}
