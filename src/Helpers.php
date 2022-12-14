<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline;

/**
 * Класс с функциями-хелперами
 */
final class Helpers
{
    /**
     * Конвертирует копейки в рубли, оставляя только 2 знака после запятой
     *
     * @param float|null $kopeks Копейки
     * @return float Рубли
     */
    public static function toRub(?float $kopeks): float
    {
        return round(abs((float)$kopeks) / 100, 2);
    }

    /**
     * Конвертирует рубли в копейки, учитывая только 2 знака после запятой
     *
     * @param float|null $rubles Рубли
     * @return int Копейки
     */
    public static function toKop(?float $rubles): int
    {
        return (int)round(abs((float)$rubles) * 100, 2);
    }

    /**
     * Генерирует случайную строку указанной длины
     *
     * @param int $length Длина, по умолчанию 8
     * @param bool $with_digits Включать ли цифры
     * @return string
     */
    public static function randomStr(int $length = 8, bool $with_digits = true): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' . ($with_digits ? '0123456789' : '');
        $result = '';
        for ($i = 0; $i < abs($length); $i++) {
            $result .= $alphabet[mt_rand(0, strlen($alphabet) - 1)];
        }
        return $result;
    }
}
