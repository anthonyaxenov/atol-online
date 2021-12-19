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
use AtolOnline\Exceptions\{
    EmptyAddUserPropNameException,
    EmptyAddUserPropValueException,
    TooLongAddUserPropNameException,
    TooLongAddUserPropValueException
};
use JetBrains\PhpStorm\{
    ArrayShape,
    Pure
};

/**
 * Класс, описывающий дополнительный реквизит пользователя
 *
 * @see https://online.atol.ru/files/API_atol_online_v4.pdf Документация, стр 32
 */
final class AdditionalUserProps extends Entity
{
    /**
     * @var string Наименование (1085)
     */
    protected string $name;

    /**
     * @var string Значение (1086)
     */
    protected string $value;

    /**
     * Конструктор объекта покупателя
     *
     * @param string $name Наименование (1227)
     * @param string $value Значение (1008)
     * @throws EmptyAddUserPropNameException
     * @throws EmptyAddUserPropValueException
     * @throws TooLongAddUserPropNameException
     * @throws TooLongAddUserPropValueException
     */
    public function __construct(string $name, string $value)
    {
        $this->setName($name)->setValue($value);
    }

    /**
     * Возвращает наименование реквизита
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Устанавливает наименование реквизита
     *
     * @param string $name
     * @return $this
     * @throws TooLongAddUserPropNameException
     * @throws EmptyAddUserPropNameException
     */
    public function setName(string $name): self
    {
        $name = trim($name);
        if (mb_strlen($name) > Constraints::MAX_LENGTH_ADD_USER_PROP_NAME) {
            throw new TooLongAddUserPropNameException($name);
        }
        if (empty($name)) {
            throw new EmptyAddUserPropNameException($name);
        }
        $this->name = $name;
        return $this;
    }

    /**
     * Возвращает установленный телефон
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Устанавливает значение реквизита
     *
     * @param string $value
     * @return $this
     * @throws TooLongAddUserPropValueException
     * @throws EmptyAddUserPropValueException
     */
    public function setValue(string $value): self
    {
        $value = trim($value);
        if (mb_strlen($value) > Constraints::MAX_LENGTH_CLIENT_NAME) {
            throw new TooLongAddUserPropValueException($value);
        }
        if (empty($value)) {
            throw new EmptyAddUserPropValueException($value);
        }
        $this->value = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    #[ArrayShape(['name' => 'string', 'value' => 'null|string'])]
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'value' => $this->getValue(),
        ];
    }
}
