<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Tests\Api;

use AtolOnline\Api\AtolClient;
use AtolOnline\Api\AtolResponse;
use AtolOnline\Api\Monitor;
use AtolOnline\Entities\Kkt;
use AtolOnline\Exceptions\AuthFailedException;
use AtolOnline\Exceptions\EmptyLoginException;
use AtolOnline\Exceptions\EmptyMonitorDataException;
use AtolOnline\Exceptions\EmptyPasswordException;
use AtolOnline\Exceptions\NotEnoughMonitorDataException;
use AtolOnline\Exceptions\TooLongLoginException;
use AtolOnline\Exceptions\TooLongPasswordException;
use AtolOnline\Helpers;
use AtolOnline\TestEnvParams;
use AtolOnline\Tests\BasicTestCase;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Набор тестов для проверки работы API-клиента на примере монитора ККТ
 */
class MonitorTest extends BasicTestCase
{
    /**
     * Возвращает объект монитора для тестирования с тестовым API АТОЛ
     *
     * @return Monitor
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    private function newTestClient(): Monitor
    {
        return (new Monitor(true))
            ->setLogin(TestEnvParams::FFD105()['login'])
            ->setPassword(TestEnvParams::FFD105()['password']);
    }

    /**
     * Тестирует успешное создание объекта монитора без аргументов конструктора
     *
     * @covers \AtolOnline\Api\Monitor::__construct
     */
    public function testConstructorWithoutArgs(): void
    {
        $client = new Monitor();
        $this->assertIsObject($client);
        $this->assertIsSameClass(Monitor::class, $client);
        $this->assertExtendsClasses([AtolClient::class], $client);
    }

    /**
     * Тестирует успешное создание объекта монитора с аргументами конструктора
     *
     * @covers \AtolOnline\Api\Monitor::__construct
     * @covers \AtolOnline\Api\Monitor::setLogin
     * @covers \AtolOnline\Api\Monitor::getLogin
     * @covers \AtolOnline\Api\Monitor::setPassword
     * @covers \AtolOnline\Api\Monitor::getPassword
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testConstructorWithArgs(): void
    {
        $client = new Monitor(false, 'login', 'password', []);
        $this->assertIsObject($client);
        $this->assertIsSameClass($client, Monitor::class);
        $this->assertExtendsClasses([AtolClient::class], $client);
    }
    //

    /**
     * Тестирует установку и возврат логина
     *
     * @covers \AtolOnline\Api\Monitor::__construct
     * @covers \AtolOnline\Api\Monitor::getLogin
     * @covers \AtolOnline\Api\Monitor::setLogin
     * @throws EmptyLoginException
     * @throws TooLongLoginException
     */
    public function testLogin(): void
    {
        $client = new Monitor(false, login: 'login');
        $this->assertSame('login', $client->getLogin());

        $client = new Monitor();
        $this->assertSame(TestEnvParams::FFD105()['login'], $client->getLogin());

        $client->setLogin('login');
        $this->assertSame(TestEnvParams::FFD105()['login'], $client->getLogin());
    }

    /**
     * Тестирует исключение при попытке передать пустой логин в конструктор
     *
     * @covers \AtolOnline\Api\Monitor::__construct
     * @covers \AtolOnline\Api\Monitor::setLogin
     * @covers \AtolOnline\Exceptions\EmptyLoginException
     */
    public function testEmptyLoginException(): void
    {
        $this->expectException(EmptyLoginException::class);
        new Monitor(login: '');
    }

    /**
     * Тестирует исключение при попытке передать слишком длинный логин в конструктор
     *
     * @covers \AtolOnline\Api\Monitor::__construct
     * @covers \AtolOnline\Api\Monitor::setLogin
     * @covers \AtolOnline\Exceptions\TooLongLoginException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testTooLongLoginException(): void
    {
        $this->expectException(TooLongLoginException::class);
        new Monitor(login: Helpers::randomStr(101));
    }

    /**
     * Тестирует установку и возврат пароля
     *
     * @covers \AtolOnline\Api\Monitor::__construct
     * @covers \AtolOnline\Api\Monitor::getPassword
     * @covers \AtolOnline\Api\Monitor::setPassword
     * @throws EmptyPasswordException
     * @throws TooLongPasswordException
     */
    public function testPassword(): void
    {
        $client = new Monitor(false, password: 'password');
        $this->assertSame('password', $client->getPassword());

        $client = new Monitor();
        $this->assertSame(TestEnvParams::FFD105()['password'], $client->getPassword());

        $client->setPassword('password');
        $this->assertSame(TestEnvParams::FFD105()['password'], $client->getPassword());
    }

    /**
     * Тестирует исключение при попытке передать пустой пароль в конструктор
     *
     * @covers \AtolOnline\Api\Monitor::__construct
     * @covers \AtolOnline\Api\Monitor::setPassword
     * @covers \AtolOnline\Exceptions\EmptyPasswordException
     */
    public function testEmptyPasswordException(): void
    {
        $this->expectException(EmptyPasswordException::class);
        new Monitor(password: '');
    }

    /**
     * Тестирует исключение при попытке передать слишком длинный пароль в конструктор
     *
     * @covers \AtolOnline\Api\Monitor::__construct
     * @covers \AtolOnline\Api\Monitor::setPassword
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testConstructorWithLongPassword(): void
    {
        $this->expectException(TooLongPasswordException::class);
        new Monitor(password: Helpers::randomStr(101));
    }

    /**
     * Тестирует установку тестового режима
     *
     * @covers \AtolOnline\Api\Monitor::__construct
     * @covers \AtolOnline\Api\Monitor::isTestMode
     * @covers \AtolOnline\Api\Monitor::setTestMode
     */
    public function testTestMode(): void
    {
        $client = new Monitor();
        $this->assertTrue($client->isTestMode());

        $client = new Monitor(true);
        $this->assertTrue($client->isTestMode());

        $client = new Monitor(false);
        $this->assertFalse($client->isTestMode());

        $client = (new Monitor())->setTestMode();
        $this->assertTrue($client->isTestMode());

        $client = (new Monitor())->setTestMode(false);
        $this->assertFalse($client->isTestMode());
    }

    /**
     * Тестирует авторизацию
     *
     * @covers \AtolOnline\Api\AtolClient::getHeaders
     * @covers \AtolOnline\Api\Monitor::sendRequest
     * @covers \AtolOnline\Api\Monitor::getAuthEndpoint
     * @covers \AtolOnline\Api\Monitor::doAuth
     * @covers \AtolOnline\Api\Monitor::auth
     * @covers \AtolOnline\Exceptions\AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     * @throws AuthFailedException
     * @throws GuzzleException
     */
    public function testAuth(): void
    {
        $this->skipIfMonitoringIsOffline();
        $result = $this->newTestClient()->auth();
        $this->assertTrue($result);
    }

    /**
     * Тестирует возврат токена после авторизации
     *
     * @depends testAuth
     * @covers  \AtolOnline\Api\Monitor::setToken
     * @covers  \AtolOnline\Api\Monitor::getToken
     * @covers  \AtolOnline\Exceptions\AuthFailedException
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testGetToken(): void
    {
        $client = new Monitor();
        $this->assertNull($client->getToken());

        $this->skipIfMonitoringIsOffline();
        $client = $this->newTestClient();
        $client->auth();
        $this->assertIsString($client->getToken());
    }

    /**
     * Тестирует возврат объекта последнего ответа от API
     *
     * @depends testAuth
     * @covers  \AtolOnline\Api\Monitor::getLastResponse
     * @covers  \AtolOnline\Exceptions\AuthFailedException
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testGetResponse(): void
    {
        $this->skipIfMonitoringIsOffline();
        $client = $this->newTestClient();
        $client->auth();
        $this->assertIsSameClass(AtolResponse::class, $client->getLastResponse());
    }

    /**
     * [Мониторинг] Тестирует получение данных о всех ККТ
     *
     * @depends testAuth
     * @covers  \AtolOnline\Api\Monitor::getMainEndpoint
     * @covers  \AtolOnline\Api\AtolClient::getUrlToMethod
     * @covers  \AtolOnline\Api\Monitor::fetchAll
     * @covers  \AtolOnline\Api\Monitor::getAll
     * @covers  \AtolOnline\Exceptions\AuthFailedException
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testMonitorGetAll(): void
    {
        $this->skipIfMonitoringIsOffline();
        $client = $this->newTestClient();
        $client->auth();
        $kkts = $client->getAll();
        $this->assertNotEmpty($client->getLastResponse()->getContent());
        $this->assertIsCollection($kkts);
        $this->assertTrue($kkts->count() > 0);
        $this->assertIsSameClass(Kkt::class, $kkts->random());
    }

    /**
     * [Мониторинг] Тестирует получение данных о конкретной ККТ
     *
     * @depends testAuth
     * @covers  \AtolOnline\Api\Monitor::getMainEndpoint
     * @covers  \AtolOnline\Api\AtolClient::getUrlToMethod
     * @covers  \AtolOnline\Api\Monitor::fetchOne
     * @covers  \AtolOnline\Api\Monitor::getOne
     * @covers  \AtolOnline\Entities\Kkt::__construct
     * @covers  \AtolOnline\Entities\Kkt::__get
     * @covers  \AtolOnline\Entities\Kkt::jsonSerialize
     * @covers  \AtolOnline\Entities\Kkt::__toString
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     * @throws EmptyMonitorDataException
     * @throws NotEnoughMonitorDataException
     * @throws Exception
     */
    public function testMonitorGetOne(): void
    {
        $this->skipIfMonitoringIsOffline();
        $client = $this->newTestClient();
        $client->auth();
        $serial_number = $client->getAll()->first()->serialNumber;
        $kkt = $client->getOne($serial_number);
        $this->assertNotEmpty($client->getLastResponse());
        $this->assertIsSameClass(Kkt::class, $kkt);
        $this->assertIsAtolable($kkt);
        $this->assertNotNull($kkt->serialNumber);
        $this->assertSame($serial_number, $kkt->serialNumber);
    }
}
