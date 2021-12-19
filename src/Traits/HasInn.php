<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Traits;

use AtolOnline\Constants\Constraints;
use AtolOnline\Exceptions\InvalidInnLengthException;

/**
 * Трейт для сущностей, которые могут иметь ИНН
 */
trait HasInn
{
    /**
     * @var string|null ИНН (1226, 1228, 1018)
     */
    protected ?string $inn = null;

    /**
     * Устанавливает ИНН
     *
     * @param string|null $inn
     * @return $this
     * @throws InvalidInnLengthException
     */
    public function setInn(?string $inn): static
    {
        if (is_string($inn)) {
            $inn = preg_replace('/[^\d]/', '', trim($inn));
            if (preg_match_all(Constraints::PATTERN_INN, $inn) === 0) {
                throw new InvalidInnLengthException($inn);
            }
        }
        $this->inn = $inn ?: null;
        return $this;
    }

    /**
     * Возвращает установленный ИНН
     *
     * @return string|null
     */
    public function getInn(): ?string
    {
        return $this->inn;
    }
}
