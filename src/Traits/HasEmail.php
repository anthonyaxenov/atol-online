<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Traits;

use AtolOnline\Constraints;
use AtolOnline\Exceptions\InvalidEmailException;
use AtolOnline\Exceptions\TooLongEmailException;

/**
 * Трейт для сущностей, которые могут иметь email
 */
trait HasEmail
{
    /**
     * @var string|null Email (1008, 1117)
     */
    protected ?string $email = null;

    /**
     * Устанавливает email
     *
     * @param string|null $email
     * @return $this
     * @throws TooLongEmailException
     * @throws InvalidEmailException
     */
    public function setEmail(?string $email): static
    {
        if (is_string($email)) {
            $email = preg_replace('/[\n\r\t]/', '', trim($email));
            if (mb_strlen($email) > Constraints::MAX_LENGTH_EMAIL) {
                throw new TooLongEmailException($email);
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                throw new InvalidEmailException($email);
            }
        }
        $this->email = $email ?: null;
        return $this;
    }

    /**
     * Возвращает установленный email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
