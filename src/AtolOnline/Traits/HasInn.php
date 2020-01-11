<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Traits;

use AtolOnline\Exceptions\AtolInnWrongLengthException;

/**
 * Добавляет объекту функционал для работы с ИНН
 *
 * @package AtolOnline\Traits
 */
trait HasInn
{
    /**
     * @var string ИНН
     */
    protected $inn;
    
    /**
     * Возвращает установленный ИНН. Тег ФФД: 1228, 1018.
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn ?? '';
    }
    
    /**
     * Устанавливает ИНН. Тег ФФД: 1228, 1018.
     * Входная строка лишается всех знаков, кроме цифр.
     *
     * @param string $inn
     * @return $this
     * @throws AtolInnWrongLengthException
     */
    public function setInn(string $inn)
    {
        $inn = preg_replace("/[^0-9]/", '', $inn);
        if (preg_match_all("/(^[0-9]{10}$)|(^[0-9]{12}$)/", $inn) == 0) {
            throw new AtolInnWrongLengthException($inn);
        }
        $this->inn = $inn;
        return $this;
    }
}