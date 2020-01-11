<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Traits;

/**
 * Свойство класса, позволяющее конвертировать рубли <-> копейки
 *
 * @package AtolOnline\Traits
 */
trait RublesKopeksConverter
{
    /**
     * Конвертирует рубли в копейки, учитывая только 2 знака после запятой
     *
     * @param float|null $rubles Рубли
     * @return int Копейки
     */
    protected static function toKop(?float $rubles = null)
    {
        return $rubles === null ? null : (int)round($rubles * 100, 2);
    }
    
    /**
     * Конвертирует копейки в рубли, оставляя только 2 знака после запятой
     *
     * @param int|null $kopeks Копейки
     * @return float Рубли
     */
    protected static function toRub(?int $kopeks = null)
    {
        return $kopeks === null ? null : round($kopeks / 100, 2);
    }
}
