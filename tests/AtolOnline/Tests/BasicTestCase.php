<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Tests;

use AtolOnline\Collections\EntityCollection;
use AtolOnline\Entities\Entity;
use AtolOnline\Entities\Item;
use AtolOnline\Entities\Payment;
use AtolOnline\Entities\Vat;
use AtolOnline\Enums\PaymentTypes;
use AtolOnline\Enums\VatTypes;
use AtolOnline\Exceptions\EmptyItemNameException;
use AtolOnline\Exceptions\InvalidEnumValueException;
use AtolOnline\Exceptions\NegativeItemPriceException;
use AtolOnline\Exceptions\NegativeItemQuantityException;
use AtolOnline\Exceptions\NegativePaymentSumException;
use AtolOnline\Exceptions\TooHighItemPriceException;
use AtolOnline\Exceptions\TooHighPaymentSumException;
use AtolOnline\Exceptions\TooLongItemNameException;
use AtolOnline\Exceptions\TooManyException;
use AtolOnline\Helpers;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Базовый класс для тестов
 */
class BasicTestCase extends TestCase
{
    //------------------------------------------------------------------------------------------------------------------
    // Методы для управления тестами, использующими тестовый АТОЛ API
    //------------------------------------------------------------------------------------------------------------------

    /**
     * Проверяет наличие подключения к ресурсу по URL
     *
     * @param string $url
     * @param int $code
     * @return bool
     */
    protected function ping(string $url, int $code): bool
    {
        try {
            $result = (new Client([
                'http_errors' => false,
                'timeout' => 3,
            ]))->request('GET', $url);
        } catch (GuzzleException) {
            return false;
        }
        return $result->getStatusCode() === $code;
    }

    /**
     * Проверяет доступность API мониторинга
     *
     * @return bool
     */
    protected function isMonitoringOnline(): bool
    {
        return $this->ping('https://testonline.atol.ru/api/auth/v1/gettoken', 400);
    }

    /**
     * Пропускает текущий тест если API мониторинга недоступен
     */
    protected function skipIfMonitoringIsOffline(): void
    {
        if (!$this->isMonitoringOnline()) {
            $this->markTestSkipped($this->getName() . ': Monitoring API is inaccessible. Skipping test.');
        }
    }

    //------------------------------------------------------------------------------------------------------------------
    // Дополнительные ассерты
    //------------------------------------------------------------------------------------------------------------------

    /**
     * Тестирует является ли объект приводимым к json-строке согласно схеме АТОЛ Онлайн
     *
     * @param Entity|EntityCollection $entity
     * @param array|null $json_structure
     * @covers \AtolOnline\Entities\Entity::__toString
     * @covers \AtolOnline\Entities\Entity::toArray
     * @covers \AtolOnline\Entities\Entity::jsonSerialize
     * @covers \AtolOnline\Collections\EntityCollection::jsonSerialize
     * @throws Exception
     */
    public function assertIsAtolable(Entity|EntityCollection $entity, ?array $json_structure = null): void
    {
        $this->assertIsArray($entity->jsonSerialize());
        $this->assertIsArray($entity->toArray());
        $this->assertEquals($entity->jsonSerialize(), $entity->toArray());
        $this->assertIsString((string)$entity);
        $this->assertJson((string)$entity);
        if (!is_null($json_structure)) {
            $this->assertEquals(json_encode($json_structure), (string)$entity);
        }
    }

    //------------------------------------------------------------------------------------------------------------------
    // Ассерты проверки наследования
    //------------------------------------------------------------------------------------------------------------------

    /**
     * Тестирует идентичность двух классов
     *
     * @param object|string $expected Ожидаемый класс
     * @param object|string $actual Фактический класс
     */
    public function assertIsSameClass(object|string $expected, object|string $actual): void
    {
        $this->assertTrue($this->checkisSameClass($expected, $actual));
    }

    /**
     * Проверяет идентичность двух классов
     *
     * @param object|string $class1
     * @param object|string $class2
     * @return bool
     */
    private function checkisSameClass(object|string $class1, object|string $class2): bool
    {
        return (is_object($class1) ? $class1::class : $class1)
            === (is_object($class2) ? $class2::class : $class2);
    }

    /**
     * Тестирует наследование класса (объекта) от указанных классов
     *
     * @param array $expected Массив ожидаемых имён классов-родителей
     * @param object|string $actual Объект или имя класса для проверки
     */
    public function assertExtendsClasses(array $expected, object|string $actual): void
    {
        $this->assertTrue($this->checkExtendsClasses($expected, $actual));
    }

    /**
     * Проверяет наследование класса (объекта) от указанных классов
     *
     * @param string[] $parents Имена классов-родителей
     * @param object|string $class Объект или имя класса для проверки
     */
    private function checkExtendsClasses(array $parents, object|string $class): bool
    {
        return !empty(array_intersect($parents, is_object($class) ? class_parents($class) : [$class]));
    }

    /**
     * Тестирует имплементацию классом (объектом) указанных интерфейсов
     *
     * @param string[] $expected Массив ожидаемых имён интерфейсов
     * @param object|string $actual Объект или имя класса для проверки
     */
    public function assertImplementsInterfaces(array $expected, object|string $actual): void
    {
        $this->assertTrue($this->checkImplementsInterfaces($expected, $actual));
    }

    /**
     * Проверяет имплементацию классом (объектом) указанных интерфейсов
     *
     * @param string[] $interfaces Имена классов-интерфейсов
     * @param object|string $class Объект или имя класса для проверки
     * @see https://www.php.net/manual/ru/function.class-implements.php
     */
    private function checkImplementsInterfaces(array $interfaces, object|string $class): bool
    {
        return !empty(array_intersect($interfaces, is_object($class) ? class_implements($class) : [$class]));
    }

    /**
     * Тестирует использование классом (объектом) указанных трейтов
     *
     * @param string[] $expected Массив ожидаемых имён трейтов
     * @param object|string $actual Объект или имя класса для проверки
     */
    public function assertUsesTraits(array $expected, object|string $actual): void
    {
        $this->assertTrue($this->checkUsesTraits($expected, $actual));
    }

    /**
     * Проверяет использование классом (объектом) указанных трейтов (исключает родителей)
     *
     * @param string[] $traits Массив ожидаемых имён трейтов
     * @param object|string $class Объект или имя класса для проверки
     * @return bool
     * @see https://www.php.net/manual/ru/function.class-uses.php#110752
     */
    private function checkUsesTraits(array $traits, object|string $class): bool
    {
        $found_traits = [];
        $check_class = is_object($class) ? $class::class : $class;
        do {
            $found_traits = array_merge(class_uses($check_class, true), $found_traits);
        } while ($check_class = get_parent_class($check_class));
        foreach ($found_traits as $trait => $same) {
            $found_traits = array_merge(class_uses($trait, true), $found_traits);
        }
        return !empty(array_intersect(array_unique($found_traits), $traits));
    }

    /**
     * Тестирует, является ли объект коллекцией
     *
     * @param mixed $value
     */
    public function assertIsCollection(mixed $value): void
    {
        $this->assertIsObject($value);
        $this->assertIsIterable($value);
        $this->assertTrue(
            $this->checkisSameClass(Collection::class, $value) ||
            $this->checkExtendsClasses([Collection::class], $value)
        );
    }

    //------------------------------------------------------------------------------------------------------------------
    // Провайдеры данных для прогона тестов
    //------------------------------------------------------------------------------------------------------------------

    /**
     * Провайдер строк, которые приводятся к null
     *
     * @return array
     */
    public function providerNullableStrings(): array
    {
        return [
            [''],
            [' '],
            [null],
            ["\n\r\t"],
        ];
    }

    /**
     * Провайдер валидных телефонов
     *
     * @return array<array<string, string>>
     */
    public function providerValidPhones(): array
    {
        return [
            ['+79991234567', '+79991234567'],
            ['79991234567', '+79991234567'],
            ['89991234567', '+89991234567'],
            ['+7 999 123 45 67', '+79991234567'],
            ['+7 (999) 123-45-67', '+79991234567'],
            ["+7 %(?9:9\"9')abc\r123\n45\t67\0", '+79991234567'],
        ];
    }

    /**
     * Провайдер телефонов, которые приводятся к null
     *
     * @return array<array<string>>
     */
    public function providerNullablePhones(): array
    {
        return array_merge(
            $this->providerNullableStrings(),
            [
                [Helpers::randomStr(10, false)],
                ["asdfgvs \n\rtt\t*/(*&%^*$%"],
            ]
        );
    }

    /**
     * Провайдер валидных email-ов
     *
     * @return array<array<string>>
     */
    public function providerValidEmails(): array
    {
        return [
            ['abc@mail.com'],
            ['abc-d@mail.com'],
            ['abc.def@mail.com'],
            ['abc.def@mail.org'],
            ['abc.def@mail-archive.com'],
        ];
    }

    /**
     * Провайдер невалидных email-ов
     *
     * @return array<array<string>>
     */
    public function providerInvalidEmails(): array
    {
        return [
            ['@example'],
            [Helpers::randomStr(15)],
            ['@example.com'],
            ['abc.def@mail'],
            ['.abc@mail.com'],
            ['example@example'],
            ['abc..def@mail.com'],
            ['abc.def@mail..com'],
            ['abc.def@mail#archive.com'],
        ];
    }

    //------------------------------------------------------------------------------------------------------------------
    // Генераторы тестовых объектов
    //------------------------------------------------------------------------------------------------------------------

    /**
     * Генерирует массив тестовых объектов предметов расчёта
     *
     * @param int $count
     * @return Item[]
     * @throws EmptyItemNameException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws TooHighItemPriceException
     * @throws TooLongItemNameException
     * @throws TooManyException
     * @throws Exception
     */
    protected function generateItemObjects(int $count = 1): array
    {
        $result = [];
        for ($i = 0; $i < abs($count); ++$i) {
            $result[] = new Item(Helpers::randomStr(), random_int(1, 100), random_int(1, 10));
        }
        return $result;
    }

    /**
     * Генерирует массив тестовых объектов оплаты
     *
     * @param int $count
     * @return Payment[]
     * @throws InvalidEnumValueException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws Exception
     */
    protected function generatePaymentObjects(int $count = 1): array
    {
        $types = PaymentTypes::toArray();
        $result = [];
        for ($i = 0; $i < abs($count); ++$i) {
            $result[] = new Payment(
                array_values($types)[random_int(min($types), max($types))],
                random_int(1, 100) * 2 / 3
            );
        }
        return $result;
    }

    /**
     * Генерирует массив тестовых объектов ставок НДС
     *
     * @param int $count
     * @return Vat[]
     * @throws InvalidEnumValueException
     * @throws Exception
     */
    protected function generateVatObjects(int $count = 1): array
    {
        $types = VatTypes::toArray();
        $result = [];
        for ($i = 0; $i < abs($count); ++$i) {
            $result[] = new Vat(
                array_values($types)[random_int(0, count($types) - 1)],
                random_int(1, 100) * 2 / 3
            );
        }
        return $result;
    }
}
