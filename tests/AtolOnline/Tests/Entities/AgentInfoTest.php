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
    Entities\AgentInfo,
    Entities\MoneyTransferOperator,
    Entities\PayingAgent,
    Entities\ReceivePaymentsOperator,
    Enums\AgentTypes,
    Exceptions\InvalidEnumValueException,
    Exceptions\InvalidInnLengthException,
    Exceptions\InvalidPhoneException,
    Exceptions\TooLongPayingAgentOperationException,
    Tests\BasicTestCase};
use Exception;

/**
 * Набор тестов для проверки работы класса агента
 */
class AgentInfoTest extends BasicTestCase
{
    /**
     * Тестирует конструктор без передачи значений и корректное приведение к json
     *
     * @covers \AtolOnline\Entities\AgentInfo
     * @covers \AtolOnline\Entities\AgentInfo::jsonSerialize
     * @throws Exception
     */
    public function testConstructorWithoutArgs(): void
    {
        $this->assertAtolable(new AgentInfo(), []);
    }

    /**
     * Тестирует конструктор с передачей значений и корректное приведение к json
     *
     * @covers \AtolOnline\Entities\AgentInfo
     * @covers \AtolOnline\Entities\AgentInfo::jsonSerialize
     * @covers \AtolOnline\Entities\AgentInfo::setType
     * @covers \AtolOnline\Entities\AgentInfo::getType
     * @covers \AtolOnline\Entities\AgentInfo::setPayingAgent
     * @covers \AtolOnline\Entities\AgentInfo::getPayingAgent
     * @covers \AtolOnline\Entities\PayingAgent::jsonSerialize
     * @covers \AtolOnline\Entities\AgentInfo::setMoneyTransferOperator
     * @covers \AtolOnline\Entities\AgentInfo::getMoneyTransferOperator
     * @covers \AtolOnline\Entities\MoneyTransferOperator::jsonSerialize
     * @covers \AtolOnline\Entities\AgentInfo::setReceivePaymentsOperator
     * @covers \AtolOnline\Entities\AgentInfo::getReceivePaymentsOperator
     * @covers \AtolOnline\Entities\ReceivePaymentsOperator::jsonSerialize
     * @throws InvalidPhoneException
     * @throws TooLongPayingAgentOperationException
     * @throws InvalidInnLengthException
     * @throws InvalidEnumValueException
     * @throws Exception
     */
    public function testConstructorWithArgs(): void
    {
        $this->assertAtolable(new AgentInfo(null), []);
        $this->assertAtolable(new AgentInfo(AgentTypes::ANOTHER), ['type' => AgentTypes::ANOTHER]);
        $this->assertAtolable(new AgentInfo(pagent: new PayingAgent()), []);
        $this->assertAtolable(new AgentInfo(mt_operator: new MoneyTransferOperator()), []);
        $this->assertAtolable(new AgentInfo(rp_operator: new ReceivePaymentsOperator()), []);

        $this->assertAtolable(new AgentInfo(
            AgentTypes::ANOTHER,
            new PayingAgent(),
            new ReceivePaymentsOperator(),
            new MoneyTransferOperator(),
        ), ['type' => AgentTypes::ANOTHER]);

        $this->assertAtolable(new AgentInfo(
            AgentTypes::ANOTHER,
            new PayingAgent('test', ['+79518888888']),
            new ReceivePaymentsOperator(['+79519999999']),
            new MoneyTransferOperator('MTO Name', '9876543210', 'London', ['+79517777777']),
        ), [
            'type' => AgentTypes::ANOTHER,
            'paying_agent' => [
                'operation' => 'test',
                'phones' => [
                    '+79518888888',
                ],
            ],
            'receive_payments_operator' => [
                'phones' => [
                    '+79519999999',
                ],
            ],
            'money_transfer_operator' => [
                'name' => 'MTO Name',
                'inn' => '9876543210',
                'address' => 'London',
                'phones' => [
                    "+79517777777",
                ],
            ],
        ]);
    }

    /**
     * Тестирует исключение при некорректном типе
     *
     * @covers \AtolOnline\Entities\AgentInfo
     * @covers \AtolOnline\Enums\AgentTypes::isValid
     * @covers \AtolOnline\Exceptions\InvalidEnumValueException
     */
    public function testInvalidEnumValueException(): void
    {
        $this->expectException(InvalidEnumValueException::class);
        new AgentInfo('qwerty');
    }
}
