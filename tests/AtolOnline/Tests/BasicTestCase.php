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

use AtolOnline\Entities\Entity;
use AtolOnline\Helpers;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Базовый класс для тестов
 */
class BasicTestCase extends TestCase
{
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

    /**
     * Тестирует является ли объект приводимым к json-строке согласно схеме АТОЛ Онлайн
     *
     * @param Entity $entity
     * @param array|null $json_structure
     * @covers \AtolOnline\Entities\Entity::jsonSerialize
     * @covers \AtolOnline\Entities\Entity::__toString
     */
    public function assertAtolable(Entity $entity, ?array $json_structure = null): void
    {
        $this->assertIsArray($entity->jsonSerialize());
        $this->assertIsString((string)$entity);
        $this->assertJson((string)$entity);
        if (!is_null($json_structure)) {
            $this->assertEquals(json_encode($json_structure), (string)$entity);
        }
    }

    /**
     * Тестирует идентичность двух классов
     *
     * @param object|string $expected
     * @param object|string $actual
     */
    public function assertIsSameClass(object|string $expected, object|string $actual): void
    {
        $this->assertTrue(Helpers::isSameClass($expected, $actual));
    }

    /**
     * Тестирует наследование класса (объекта) от указанных классов
     *
     * @param object|string $class
     * @param string[] $parents
     */
    public function assertExtendsClasses(object|string $class, array $parents): void
    {
        $this->assertTrue(Helpers::checkExtendsClasses($class, $parents));
    }

    /**
     * Тестирует имплементацию классом (объектом) указанных интерфейсов
     *
     * @param object|string $class
     * @param string[] $interfaces
     */
    public function assertImplementsInterfaces(object|string $class, array $interfaces): void
    {
        $this->assertTrue(Helpers::checkImplementsInterfaces($class, $interfaces));
    }

    /**
     * Тестирует использование классом (объектом) указанных трейтов
     *
     * @param object|string $class
     * @param string[] $traits
     */
    public function assertUsesTraits(object|string $class, array $traits): void
    {
        $this->assertTrue(Helpers::checkUsesTraits($traits, $class));
    }

    /**
     * Тестирует, является ли объект коллекцией
     *
     * @param mixed $expected
     */
    public function assertIsCollection(mixed $expected): void
    {
        $this->assertIsObject($expected);
        $this->assertIsIterable($expected);
        $this->assertIsSameClass($expected, Collection::class);
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Провайдер строк, которые приводятся к null
     *
     * @return array<array<string|null>>
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
}
