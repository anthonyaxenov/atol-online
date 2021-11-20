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

/**
 * Класс CorrectionInfo, описывающий данные чек коррекции
 */
class CorrectionInfo extends Entity
{
    /**
     * @var string Тип коррекции. Тег ФФД - 1173.
     */
    protected string $type;

    /**
     * @var string Дата документа основания для коррекции. Тег ФФД - 1178.
     */
    protected string $base_date;

    /**
     * @var string Номер документа основания для коррекции. Тег ФФД - 1179.
     */
    protected string $base_number;

    /**
     * CorrectionInfo constructor.
     *
     * @param string|null $type Тип коррекции
     * @param string|null $base_date Дата документа
     * @param string|null $base_number Номер документа
     */
    public function __construct(
        ?string $type = null,
        ?string $base_date = null,
        ?string $base_number = null
    ) {
        $type && $this->setType($type);
        $base_date && $this->setDate($base_date);
        $base_number && $this->setNumber($base_number);
    }

    /**
     * Возвращает номер документа основания для коррекции.
     * Тег ФФД - 1179.
     *
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->base_number;
    }

    /**
     * Устанавливает номер документа основания для коррекции.
     * Тег ФФД - 1179.
     *
     * @param string $number
     * @return $this
     */
    public function setNumber(string $number): CorrectionInfo
    {
        $this->base_number = trim($number);
        return $this;
    }

    /**
     * Возвращает дату документа основания для коррекции.
     * Тег ФФД - 1178.
     *
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->base_date;
    }

    /**
     * Устанавливает дату документа основания для коррекции.
     * Тег ФФД - 1178.
     *
     * @param string $date Строка в формате d.m.Y
     * @return $this
     */
    public function setDate(string $date): CorrectionInfo
    {
        $this->base_date = $date;
        return $this;
    }

    /**
     * Возвращает тип коррекции.
     * Тег ФФД - 1173.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Устанавливает тип коррекции.
     * Тег ФФД - 1173.
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): CorrectionInfo
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): object
    {
        return (object)[
            'type' => $this->getType() ?? '', // обязателен
            'base_date' => $this->getDate() ?? '', // обязателен
            'base_number' => $this->getNumber() ?? '', // обязателен
        ];
    }
}