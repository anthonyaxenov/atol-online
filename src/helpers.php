<?php

if (!function_exists('valid_strlen')) {
    /**
     * Возвращает корректную длину строки
     *
     * @param string $value
     * @return int
     */
    function valid_strlen(string $value): int
    {
        return function_exists('mb_strlen')
            ? mb_strlen($value)
            : strlen($value);
    }
}
