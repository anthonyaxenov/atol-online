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
     * @param int|null $kopeks Копейки
     * @return float Рубли
     */
    public static function KopToRub(?int $kopeks): float
    {
        return round(abs((int)$kopeks) / 100, 2);
    }

    /**
     * Конвертирует рубли в копейки, учитывая только 2 знака после запятой
     *
     * @param float|null $rubles Рубли
     * @return int Копейки
     */
    public static function RubToKop(?float $rubles): int
    {
        return (int)round(abs((float)$rubles) * 100, 2);
    }

    /**
     * Генерирует случайную строку указанной длины
     *
     * @param int $length Длина, по умолчнанию 8
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

    /**
     * Проверяет идентичность двух классов
     *
     * @param object|string $class1
     * @param object|string $class2
     * @return bool
     */
    public static function isSameClass(object|string $class1, object|string $class2): bool
    {
        return (is_object($class1) ? $class1::class : $class1) === (is_object($class2) ? $class2::class : $class2);
    }

    /**
     * Тестирует наследование класса (объекта) от указанных классов
     *
     * @param object|string $class Объект или имя класса для проверки
     * @param string[] $parents Имена классов-родителей
     * @see https://www.php.net/manual/ru/function.class-parents.php
     */
    public static function checkExtendsClasses(object|string $class, array $parents): bool
    {
        return self::checkClassesIntersection($parents, $class, 'class_parents');
    }

    /**
     * Тестирует имплементацию классом (объектом) указанных интерфейсов
     *
     * @param object|string $actual Объект или имя класса для проверки
     * @param string[] $interfaces Имена классов-интерфейсов
     * @see https://www.php.net/manual/ru/function.class-implements.php
     */
    public static function checkImplementsInterfaces(object|string $actual, array $interfaces): bool
    {
        return self::checkClassesIntersection($interfaces, $actual, 'class_implements');
    }

    /**
     * Тестирует использование классом (объектом) указанных трейтов
     *
     * @param object|string $class
     * @param string[] $traits
     * @return bool
     * @see https://www.php.net/manual/ru/function.class-uses.php
     */
    public static function checkUsesTraits(array $traits, object|string $class): bool
    {
        return self::checkClassesIntersection($traits, $class, 'class_uses');
    }

    /**
     * Проверяет пересечение классов указанной функцией SPL
     *
     * @param object|string $class Класс для проверки на вхождение, или объект, класс коего нужно проверить
     * @param string[] $classes Массив классов, вхождение в который нужно проверить
     * @param string $function class_parents|class_implements|class_uses
     */
    protected static function checkClassesIntersection(array $classes, object|string $class, string $function): bool
    {
        $actual_classes = is_object($class) ? $function($class) : [$class::class];
        return is_array($actual_classes)
            && !empty(array_intersect($classes, $actual_classes));
    }
}