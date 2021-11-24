<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Tests\Entities;

use AtolOnline\{
    Entities\PayingAgent,
    Exceptions\InvalidPhoneException,
    Exceptions\TooLongPayingAgentOperationException,
    Helpers,
    Tests\BasicTestCase};

/**
 * Набор тестов для проверки работы класса платёжного агента
 */
class PayingAgentTest extends BasicTestCase
{
    /**
     * Тестирует конструктор без передачи значений и корректное приведение к json
     *
     * @covers \AtolOnline\Entities\PayingAgent
     * @covers \AtolOnline\Entities\PayingAgent::jsonSerialize
     */
    public function testConstructorWithoutArgs(): void
    {
        $this->assertEquals('[]', (string)(new PayingAgent()));
    }

    /**
     * Тестирует конструктор с передачей значений и корректное приведение к json
     *
     * @covers \AtolOnline\Entities\PayingAgent
     * @covers \AtolOnline\Entities\PayingAgent::jsonSerialize
     * @covers \AtolOnline\Entities\PayingAgent::setOperation
     * @covers \AtolOnline\Entities\PayingAgent::setPhones
     * @covers \AtolOnline\Entities\PayingAgent::getOperation
     * @covers \AtolOnline\Entities\PayingAgent::getPhones
     * @throws InvalidPhoneException
     * @throws TooLongPayingAgentOperationException
     */
    public function testConstructorWithArgs(): void
    {
        $operation = Helpers::randomStr();
        $this->assertAtolable(new PayingAgent(
            $operation,
            ['+122997365456'],
        ), [
            'operation' => $operation,
            'phones' => ['+122997365456'],
        ]);
        $this->assertAtolable(
            new PayingAgent($operation),
            ['operation' => $operation]
        );
        $this->assertAtolable(
            new PayingAgent(phones: ['+122997365456']),
            ['phones' => ['+122997365456']]
        );
    }

    /**
     * Тестирует установку операций, которые приводятся к null
     *
     * @param mixed $name
     * @dataProvider providerNullableStrings
     * @covers       \AtolOnline\Entities\PayingAgent
     * @covers       \AtolOnline\Entities\PayingAgent::setOperation
     * @covers       \AtolOnline\Entities\PayingAgent::getOperation
     * @throws TooLongPayingAgentOperationException
     */
    public function testNullableOperations(mixed $name): void
    {
        $this->assertNull((new PayingAgent())->setOperation($name)->getOperation());
    }

    /**
     * Тестирует установку невалидной операции
     *
     * @covers \AtolOnline\Entities\PayingAgent
     * @covers \AtolOnline\Entities\PayingAgent::setOperation
     * @covers \AtolOnline\Exceptions\TooLongPayingAgentOperationException
     */
    public function testTooLongPayingAgentOperationException(): void
    {
        $this->expectException(TooLongPayingAgentOperationException::class);
        (new PayingAgent())->setOperation(Helpers::randomStr(25));
    }

    /**
     * Провайдер массивов телефонов, которые приводятся к null
     *
     * @return array<array>
     */
    public function providerNullablePhonesArrays(): array
    {
        return [
            [[]],
            [null],
            [collect()],
        ];
    }

    /**
     * Тестирует установку пустых телефонов
     *
     * @dataProvider providerNullablePhonesArrays
     * @covers       \AtolOnline\Entities\PayingAgent
     * @covers       \AtolOnline\Entities\PayingAgent::setPhones
     * @covers       \AtolOnline\Entities\PayingAgent::getPhones
     * @throws InvalidPhoneException
     * @throws TooLongPayingAgentOperationException
     */
    public function testNullablePhones(mixed $phones): void
    {
        $agent = new PayingAgent(phones: $phones);
        $this->assertIsCollection($agent->getPhones());
        $this->assertTrue($agent->getPhones()->isEmpty());
    }

    /**
     * Тестирует установку невалидных телефонов
     *
     * @covers \AtolOnline\Entities\PayingAgent
     * @covers \AtolOnline\Entities\PayingAgent::setPhones
     * @covers \AtolOnline\Exceptions\InvalidPhoneException
     * @throws InvalidPhoneException
     */
    public function testInvalidPhoneException(): void
    {
        $this->expectException(InvalidPhoneException::class);
        (new PayingAgent())->setPhones([
            '12345678901234567', // good
            '+123456789012345678', // good
            '12345678901234567890', // bad
            '+12345678901234567890', // bad
        ]);
    }
}
