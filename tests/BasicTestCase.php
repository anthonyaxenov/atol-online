<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnlineTests;

use AtolOnline\Entities\Entity;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicTestCase
 */
class BasicTestCase extends TestCase
{
    /**
     * Проверяет наличие подключения к ресурсу по URL
     *
     * @param string $url
     * @param int $code
     * @return bool
     * @throws GuzzleException
     */
    protected function ping(string $url, int $code): bool
    {
        $result = (new Client(['http_errors' => false]))->request('GET', $url);
        //$this->assertEquals(200, $result->getStatusCode());
        return $result->getStatusCode() === $code;
    }

    /**
     * Проверяет доступность API мониторинга
     *
     * @return bool
     * @throws GuzzleException
     */
    protected function isMonitoringOnline(): bool
    {
        return $this->ping('https://testonline.atol.ru/api/auth/v1/gettoken', 400);
    }

    /**
     * Пропускает текущий тест если API мониторинга недоступно
     *
     * @throws GuzzleException
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
     * @param array $json_structure
     * @covers \AtolOnline\Entities\Entity::jsonSerialize
     * @covers \AtolOnline\Entities\Entity::__toString
     */
    public function assertAtolable(Entity $entity, array $json_structure = []): void
    {
        $this->assertIsObject($entity);
        $this->assertIsObject($entity->jsonSerialize());
        $this->assertIsString((string)$entity);
        $this->assertJson((string)$entity);
        if ($json_structure) {
            $this->assertEquals(json_encode($json_structure), (string)$entity);
        }
    }

    /**
     * Тестирует идентичность двух классов
     *
     * @param object|string $expected
     * @param object|string $actual
     */
    public function assertIsSameClass(object|string $expected, object|string $actual)
    {
        $this->assertEquals(
            is_object($expected) ? $expected::class : $expected,
            is_object($actual) ? $actual::class : $actual
        );
    }

    /**
     * Тестирует наследование класса (объекта) от указанных классов
     *
     * @param string[] $parents
     * @param object|string $actual
     */
    public function assertExtendsClasses(array $parents, object|string $actual)
    {
        $this->checkClassesIntersection($parents, $actual, 'class_parents');
    }

    /**
     * Тестирует имплементацию классом (объектом) указанных интерфейсов
     *
     * @param string[] $parents
     * @param object|string $actual
     */
    public function assertImplementsInterfaces(array $parents, object|string $actual)
    {
        $this->checkClassesIntersection($parents, $actual, 'class_implements');
    }

    /**
     * Тестирует использование классом (объектом) указанных трейтов
     *
     * @param string[] $parents
     * @param object|string $actual
     */
    public function assertUsesTraits(array $parents, object|string $actual)
    {
        $this->checkClassesIntersection($parents, $actual, 'class_uses');
    }

    /**
     * Проверяет пересечение классов указанной функцией SPL
     *
     * @param object|string $class Класс для проверки на вхождение, или объект, класс коего нужно проверить
     * @param array $classes Массив классов, вхождение в который нужно проверить
     * @param string $function class_parents|class_implements|class_uses
     */
    protected function checkClassesIntersection(array $classes, object|string $class, string $function): void
    {
        $actual_classes = is_object($class) ? $function($class) : [$class::class];
        $this->assertIsArray($actual_classes);
        $this->assertNotEmpty(array_intersect($classes, $actual_classes));
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
}