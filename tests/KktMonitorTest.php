<?php

namespace AtolOnlineTests;

use AtolOnline\Api\AtolClient;
use AtolOnline\Api\KktMonitor;
use AtolOnline\Api\KktResponse;
use AtolOnline\Exceptions\AuthFailedException;
use AtolOnline\Exceptions\EmptyLoginException;
use AtolOnline\Exceptions\EmptyPasswordException;
use AtolOnline\Exceptions\TooLongLoginException;
use AtolOnline\Exceptions\TooLongPasswordException;
use AtolOnline\Helpers;
use AtolOnline\TestEnvParams;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Набор тестов для проверки работы API-клиента на примере монитора ККТ
 */
class KktMonitorTest extends BasicTestCase
{
    /**
     * Тестирует успешное создание объекта монитора без аргументов конструктора
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     */
    public function testConstructorWithoutArgs()
    {
        $client = new KktMonitor();
        $this->assertIsObject($client);
        $this->assertIsSameClass(KktMonitor::class, $client);
        $this->assertExtendsClasses([AtolClient::class], $client);
    }

    /**
     * Тестирует успешное создание объекта монитора с аргументами конструктора
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::setLogin
     * @covers \AtolOnline\Api\KktMonitor::getLogin
     * @covers \AtolOnline\Api\KktMonitor::setPassword
     * @covers \AtolOnline\Api\KktMonitor::getPassword
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testConstructorWithArgs()
    {
        $client = new KktMonitor(false, 'login', 'password', []);
        $this->assertIsObject($client);
        $this->assertIsSameClass(KktMonitor::class, $client);
        $this->assertExtendsClasses([AtolClient::class], $client);
    }

    /**
     * Тестирует установку и возврат логина
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::getLogin
     * @covers \AtolOnline\Api\KktMonitor::setLogin
     * @throws EmptyLoginException
     * @throws TooLongLoginException
     */
    public function testLogin()
    {
        $client = new KktMonitor(login: 'login');
        $this->assertEquals('login', $client->getLogin());

        $client = new KktMonitor();
        $this->assertNull($client->getLogin());

        $client->setLogin('login');
        $this->assertEquals('login', $client->getLogin());
    }

    /**
     * Тестирует исключение при попытке передать пустой логин в конструктор
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::setLogin
     * @covers \AtolOnline\Exceptions\EmptyLoginException
     */
    public function testEmptyLoginException()
    {
        $this->expectException(EmptyLoginException::class);
        new KktMonitor(login: '');
    }

    /**
     * Тестирует исключение при попытке передать слишком длинный логин в конструктор
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::setLogin
     * @covers \AtolOnline\Exceptions\TooLongLoginException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testTooLongLoginException()
    {
        $this->expectException(TooLongLoginException::class);
        new KktMonitor(login: Helpers::randomStr(101));
    }

    /**
     * Тестирует установку и возврат пароля
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::getPassword
     * @covers \AtolOnline\Api\KktMonitor::setPassword
     * @throws EmptyPasswordException
     * @throws TooLongPasswordException
     */
    public function testPassword()
    {
        $client = new KktMonitor(password: 'password');
        $this->assertEquals('password', $client->getPassword());

        $client = new KktMonitor();
        $this->assertNull($client->getPassword());

        $client->setPassword('password');
        $this->assertEquals('password', $client->getPassword());
    }

    /**
     * Тестирует исключение при попытке передать пустой пароль в конструктор
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::setPassword
     * @covers \AtolOnline\Exceptions\EmptyPasswordException
     */
    public function testEmptyPasswordException()
    {
        $this->expectException(EmptyPasswordException::class);
        new KktMonitor(password: '');
    }

    /**
     * Тестирует исключение при попытке передать слишком длинный пароль в конструктор
     *
     * @covers \AtolOnline\Api\KktMonitor::__construct
     * @covers \AtolOnline\Api\KktMonitor::setPassword
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
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

    /**
     * Возвращает объект клиента для тестирования с тестовым API АТОЛ
     *
     * @return AtolClient
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    private function newTestClient(): KktMonitor
    {
        $credentials = TestEnvParams::FFD105();
        return (new KktMonitor(true))
            ->setLogin($credentials['login'])
            ->setPassword($credentials['password']);
    }

    /**
     * Тестирует авторизацию
     *
     * @covers \AtolOnline\Api\AtolClient::getHeaders
     * @covers \AtolOnline\Api\KktMonitor::sendRequest
     * @covers \AtolOnline\Api\KktMonitor::getAuthEndpoint
     * @covers \AtolOnline\Api\KktMonitor::doAuth
     * @covers \AtolOnline\Api\KktMonitor::auth
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     * @throws AuthFailedException
     * @throws GuzzleException
     */
    public function testAuth()
    {
        $this->skipIfMonitoringIsOffline();
        $result = $this->newTestClient()->auth();
        $this->assertTrue($result);
    }

    /**
     * Тестирует возврат токена после авторизации
     *
     * @depends testAuth
     * @covers  \AtolOnline\Api\KktMonitor::setToken
     * @covers  \AtolOnline\Api\KktMonitor::getToken
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testGetToken()
    {
        $client = new KktMonitor();
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
     * @covers  \AtolOnline\Api\KktMonitor::getResponse
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testGetResponse()
    {
        $this->skipIfMonitoringIsOffline();
        $client = $this->newTestClient();
        $client->auth();
        $this->assertIsSameClass(KktResponse::class, $client->getResponse());
    }

    /**
     * [Мониторинг] Тестирует получение данных о всех ККТ
     *
     * @depends testAuth
     * @covers  \AtolOnline\Api\KktMonitor::getMainEndpoint
     * @covers  \AtolOnline\Api\AtolClient::getUrlToMethod
     * @covers  \AtolOnline\Api\KktMonitor::fetchAll
     * @covers  \AtolOnline\Api\KktMonitor::getAll
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testMonitorGetAll()
    {
        $this->skipIfMonitoringIsOffline();
        $client = $this->newTestClient();
        $client->auth();
        $kkts = $client->getAll();
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertIsCollection($kkts);
        $this->assertTrue($kkts->count() > 0);
    }

    /**
     * [Мониторинг] Тестирует получение данных о конкретной ККТ
     *
     * @depends testAuth
     * @covers  \AtolOnline\Api\KktMonitor::getMainEndpoint
     * @covers  \AtolOnline\Api\AtolClient::getUrlToMethod
     * @covers  \AtolOnline\Api\KktMonitor::fetchOne
     * @covers  \AtolOnline\Api\KktMonitor::getOne
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testGetOne()
    {
        $this->skipIfMonitoringIsOffline();
        $client = $this->newTestClient();
        $client->auth();
        $kkts = $client->getAll();
        $serial_number = $kkts->first()->serialNumber;
        $client->getOne($serial_number);
        $this->assertIsSameClass(KktResponse::class, $client->getResponse());
        $this->assertNotEmpty($client->getResponse());
        $this->assertNotNull($client->getResponse()->data->serialNumber);
        $this->assertEquals($serial_number, $client->getResponse()->data->serialNumber);
    }
}
