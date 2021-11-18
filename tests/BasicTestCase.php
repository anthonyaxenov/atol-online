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
use PHPUnit\Framework\TestCase;

/**
 * Class BasicTestCase
 */
class BasicTestCase extends TestCase
{
    /**
     * @todo требуется рефакторинг
     */
    public function setUp(): void
    {
        //parent::setUp();
        defined('ATOL_KKT_GROUP') ?: define('ATOL_KKT_GROUP', 'v4-online-atol-ru_4179');
        defined('ATOL_KKT_LOGIN') ?: define('ATOL_KKT_LOGIN', 'v4-online-atol-ru');
        defined('ATOL_KKT_PASS') ?: define('ATOL_KKT_PASS', 'iGFFuihss');
        defined('ATOL_CALLBACK_URL') ?: define('ATOL_CALLBACK_URL', 'http://example.com/callback');
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