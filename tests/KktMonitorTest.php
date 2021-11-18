<?php

namespace AtolOnlineTests;

use AtolOnline\Api\AtolClient;
use AtolOnline\Api\KktMonitor;
use AtolOnline\Exceptions\EmptyLoginException;
use AtolOnline\Exceptions\EmptyPasswordException;
use AtolOnline\Exceptions\TooLongLoginException;
use AtolOnline\Exceptions\TooLongPasswordException;
use AtolOnline\Helpers;

/**
 * Набор тестов для проверки работы API-клиента на примере монитора ККТ
 */
class KktMonitorTest extends BasicTestCase
{
    /**
     * Тестирует успешное создание объекта монитора без аргументов конструктора
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::getLogin
     * @covers \AtolOnline\Api\KktMonitor::getPassword
     */
    public function testConstructorWithoutArgs()
    {
        $client = new KktMonitor();
        $this->assertIsObject($client);
        $this->assertIsSameClass(KktMonitor::class, $client);
        $this->assertExtendsClasses([AtolClient::class], $client);
        $this->assertNull($client->getLogin());
        $this->assertNull($client->getPassword());
    }

    /**
     * Тестирует успешное создание объекта монитора с аргументами конструктора
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::setLogin
     * @covers \AtolOnline\Api\KktMonitor::setPassword
     * @covers \AtolOnline\Api\KktMonitor::getLogin
     * @covers \AtolOnline\Api\KktMonitor::getPassword
     */
    public function testConstructorWithArgs()
    {
        $client = new KktMonitor(false, 'login', 'password', []);
        $this->assertIsObject($client);
        $this->assertIsSameClass(KktMonitor::class, $client);
        $this->assertExtendsClasses([AtolClient::class], $client);
        //$this->assertFalse($client->isTestMode());
        //$this->assertEquals('login', $client->getLogin());
        //$this->assertEquals('password', $client->getPassword());
    }

    /**
     * Тестирует исключение при попытке передать пустой логин в конструктор
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::setLogin
     */
    public function testConstructorWithShortLogin()
    {
        $this->expectException(EmptyLoginException::class);
        new KktMonitor(login: '');
    }

    /**
     * Тестирует исключение при попытке передать слишком длинный логин в конструктор
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::setLogin
     */
    public function testConstructorWithLongLogin()
    {
        $this->expectException(TooLongLoginException::class);
        new KktMonitor(login: Helpers::randomStr(101));
    }

    /**
     * Тестирует исключение при попытке передать пустой пароль в конструктор
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::setPassword
     */
    public function testConstructorWithShortPassword()
    {
        $this->expectException(EmptyPasswordException::class);
        new KktMonitor(password: '');
    }

    /**
     * Тестирует исключение при попытке передать слишком длинный пароль в конструктор
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::setPassword
     */
    public function testConstructorWithLongPassword()
    {
        $this->expectException(TooLongPasswordException::class);
        new KktMonitor(password: Helpers::randomStr(101));
    }

    /**
     * Тестирует установку тестового режима
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::isTestMode
     * @covers \AtolOnline\Api\KktMonitor::setTestMode
     */
    public function testTestMode()
    {
        $client = new KktMonitor();
        $this->assertTrue($client->isTestMode());

        $client = new KktMonitor(true);
        $this->assertTrue($client->isTestMode());

        $client = new KktMonitor(false);
        $this->assertFalse($client->isTestMode());

        $client = (new KktMonitor())->setTestMode();
        $this->assertTrue($client->isTestMode());

        $client = (new KktMonitor())->setTestMode(true);
        $this->assertTrue($client->isTestMode());

        $client = (new KktMonitor())->setTestMode(false);
        $this->assertFalse($client->isTestMode());
    }

    public function todo_testGetToken()
    {
    }

    public function todo_testGetResponse()
    {
        //$this->skipIfMonitoringIsOffline();
    }

    public function todo_testSetPassword()
    {
    }

    public function todo_testAuth()
    {
    }

    public function todo_testGetAll()
    {
    }

    public function todo_testSetToken()
    {
    }

    public function todo_testGetOne()
    {
    }

    public function todo_testSetLogin()
    {
    }
}
