<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Traits;

use AtolOnline\{Constants\Constraints, Exceptions\AtolEmailTooLongException, Exceptions\AtolEmailValidateException};

/**
 * Добавляет объекту функционал для работы с email
 *
 * @package AtolOnline\Traits
 */
trait HasEmail
{
    /**
     * @var string Почта
     */
    protected $email;
    
    /**
     * Возвращает установленную почту. Тег ФФД: 1008, 1117.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Устанавливает почту. Тег ФФД: 1008, 1117.
     *
     * @param string $email
     * @return $this
     * @throws \AtolOnline\Exceptions\AtolEmailTooLongException Слишком длинный email
     * @throws \AtolOnline\Exceptions\AtolEmailValidateException Невалидный email
     */
    public function setEmail(string $email)
    {
        $email = trim($email);
        if (valid_strlen($email) > Constraints::MAX_LENGTH_EMAIL) {
            throw new AtolEmailTooLongException($email, Constraints::MAX_LENGTH_EMAIL);
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AtolEmailValidateException($email);
        }
        $this->email = $email;
        return $this;
    }
}