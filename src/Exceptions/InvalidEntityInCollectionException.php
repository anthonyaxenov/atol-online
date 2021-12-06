<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Exceptions;

/**
 * Исключение, возникающее при наличии некорректных объектов в коллекции
 */
class InvalidEntityInCollectionException extends AtolException
{
    /**
     * Конструктор
     *
     * @param string $collection_class
     * @param string $expected_class
     * @param mixed $actual
     */
    public function __construct(string $collection_class, string $expected_class, mixed $actual)
    {
        if (is_object($actual)) {
            $actual = $actual::class;
        } elseif (is_scalar($actual)) {
            $actual = '(' . gettype($actual) . ')' . var_export($actual, true);
        }
        parent::__construct(
            "Коллекция $collection_class должна содержать объекты $expected_class, найден $actual"
        );
    }
}
