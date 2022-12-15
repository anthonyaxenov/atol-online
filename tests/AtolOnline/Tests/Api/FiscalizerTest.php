<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Tests\Api;

use AtolOnline\{
    Constraints,
    Helpers,
    TestEnvParams,
    Tests\BasicTestCase};
use AtolOnline\Api\{
    AtolClient,
    Fiscalizer};
use AtolOnline\Exceptions\{
    AuthFailedException,
    EmptyCorrectionNumberException,
    EmptyGroupException,
    EmptyItemNameException,
    EmptyItemsException,
    EmptyLoginException,
    EmptyPasswordException,
    InvalidCallbackUrlException,
    InvalidCorrectionDateException,
    InvalidEntityInCollectionException,
    InvalidEnumValueException,
    InvalidInnLengthException,
    InvalidPaymentAddressException,
    InvalidUuidException,
    NegativeItemPriceException,
    NegativeItemQuantityException,
    NegativePaymentSumException,
    TooHighItemPriceException,
    TooHighPaymentSumException,
    TooLongCallbackUrlException,
    TooLongItemNameException,
    TooLongPaymentAddressException,
    TooManyException};
use GuzzleHttp\Exception\GuzzleException;

/**
 * Набор тестов для проверки работы фискализатора
 */
class FiscalizerTest extends BasicTestCase
{
    /**
     * @var array Массив UUID-ов результатов регистрации документов для переиспользования
     *            в тестах получения их статусов фискализации
     */
    private static array $registered_uuids = [];

    /**
     * Тестирует успешное создание объекта фискализатора без аргументов конструктора
     *
     * @return void
     * @covers \AtolOnline\Api\Fiscalizer
     */
    public function testConstructorWithourArgs(): void
    {
        $fisc = new Fiscalizer();
        $this->assertIsObject($fisc);
        $this->assertIsSameClass(Fiscalizer::class, $fisc);
        $this->assertExtendsClasses([AtolClient::class], $fisc);
    }

    /**
     * Тестирует установку и возврат группы ККТ
     *
     * @return void
     * @covers \AtolOnline\Api\Fiscalizer
     * @covers \AtolOnline\Api\Fiscalizer::getGroup
     * @covers \AtolOnline\Api\Fiscalizer::setGroup
     */
    public function testGroup(): void
    {
        // test mode
        $this->assertSame(
            TestEnvParams::FFD105()['group'],
            (new Fiscalizer(group: 'group'))->getGroup()
        );
        // prod mode
        $this->assertSame('group', (new Fiscalizer(false, group: 'group'))->getGroup());
        $this->assertNull((new Fiscalizer(false))->getGroup());
    }

    /**
     * Тестирует выброс исключения при попытке передать пустую группу ККТ в конструктор
     *
     * @return void
     * @covers \AtolOnline\Api\Fiscalizer
     * @covers \AtolOnline\Api\Fiscalizer::setGroup
     * @covers \AtolOnline\Exceptions\EmptyGroupException
     */
    public function testEmptyGroupException(): void
    {
        $this->expectException(EmptyGroupException::class);
        new Fiscalizer(group: "\n\r \0\t");
    }

    /**
     * Тестирует выброс исключения при попытке установить слишком длинный адрес колбека
     *
     * @return void
     * @covers \AtolOnline\Api\Fiscalizer::setCallbackUrl
     * @covers \AtolOnline\Exceptions\TooLongCallbackUrlException
     * @throws InvalidCallbackUrlException
     * @throws TooLongCallbackUrlException
     */
    public function testTooLongCallbackUrlException(): void
    {
        $this->expectException(TooLongCallbackUrlException::class);
        (new Fiscalizer())->setCallbackUrl(Helpers::randomStr(Constraints::MAX_LENGTH_CALLBACK_URL + 1));
    }

    /**
     * Тестирует выброс исключения при попытке установить слишком длинный адрес колбека
     *
     * @return void
     * @covers \AtolOnline\Api\Fiscalizer::setCallbackUrl
     * @covers \AtolOnline\Exceptions\InvalidCallbackUrlException
     * @throws InvalidCallbackUrlException
     * @throws TooLongCallbackUrlException
     */
    public function testInvalidCallbackUrlException(): void
    {
        $this->expectException(InvalidCallbackUrlException::class);
        (new Fiscalizer())->setCallbackUrl(Helpers::randomStr());
    }

    /**
     * Тестирует обнуление адреса колбека
     *
     * @param mixed $param
     * @return void
     * @covers       \AtolOnline\Api\Fiscalizer::setCallbackUrl
     * @covers       \AtolOnline\Api\Fiscalizer::getCallbackUrl
     * @dataProvider providerNullableStrings
     * @throws InvalidCallbackUrlException
     * @throws TooLongCallbackUrlException
     */
    public function testNullableCallbackUrl(mixed $param): void
    {
        $this->assertNull((new Fiscalizer())->setCallbackUrl($param)->getCallbackUrl());
    }

    /**
     * Тестирует регистрацию документа прихода
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt::sell
     * @covers \AtolOnline\Api\Fiscalizer::sell
     * @covers \AtolOnline\Api\Fiscalizer::getFullUrl
     * @covers \AtolOnline\Api\Fiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::registerDocument
     * @throws AuthFailedException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooLongPaymentAddressException
     * @throws TooManyException
     * @throws GuzzleException
     * @throws InvalidEnumValueException
     */
    public function testSell(): void
    {
        $fisc_result = $this->newReceipt()->sell(new Fiscalizer());
        $this->assertTrue($fisc_result->isSuccessful());
        $this->assertSame('wait', $fisc_result->getContent()->status);
        self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует регистрацию документа возврата прихода
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt::sellRefund
     * @covers \AtolOnline\Api\Fiscalizer::sellRefund
     * @covers \AtolOnline\Api\Fiscalizer::getFullUrl
     * @covers \AtolOnline\Api\Fiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::registerDocument
     * @throws AuthFailedException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooLongPaymentAddressException
     * @throws TooManyException
     * @throws GuzzleException
     * @throws InvalidEnumValueException
     */
    public function testSellRefund(): void
    {
        $fisc_result = $this->newReceipt()->sellRefund(new Fiscalizer());
        $this->assertTrue($fisc_result->isSuccessful());
        $this->assertSame('wait', $fisc_result->getContent()->status);
        self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует регистрацию документа возврата прихода
     *
     * @return void
     * @covers \AtolOnline\Entities\Correction::sellCorrect
     * @covers \AtolOnline\Api\Fiscalizer::sellCorrect
     * @covers \AtolOnline\Api\Fiscalizer::getFullUrl
     * @covers \AtolOnline\Api\Fiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::registerDocument
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws TooLongPaymentAddressException
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     * @throws InvalidEnumValueException
     */
    public function testSellCorrect(): void
    {
        $fisc_result = $this->newCorrection()->sellCorrect(new Fiscalizer());
        $this->assertTrue($fisc_result->isSuccessful());
        $this->assertSame('wait', $fisc_result->getContent()->status);
        //self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует регистрацию документа расхода
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt::buy
     * @covers \AtolOnline\Api\Fiscalizer::buy
     * @covers \AtolOnline\Api\Fiscalizer::getFullUrl
     * @covers \AtolOnline\Api\Fiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::registerDocument
     * @throws AuthFailedException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooLongPaymentAddressException
     * @throws TooManyException
     * @throws GuzzleException
     * @throws InvalidEnumValueException
     */
    public function testBuy(): void
    {
        $fisc_result = $this->newReceipt()->buy(new Fiscalizer());
        $this->assertTrue($fisc_result->isSuccessful());
        $this->assertSame('wait', $fisc_result->getContent()->status);
        //self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует регистрацию документа возврата расхода
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt::buyRefund
     * @covers \AtolOnline\Api\Fiscalizer::buyRefund
     * @covers \AtolOnline\Api\Fiscalizer::getFullUrl
     * @covers \AtolOnline\Api\Fiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::registerDocument
     * @throws AuthFailedException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooLongPaymentAddressException
     * @throws TooManyException
     * @throws GuzzleException
     * @throws InvalidEnumValueException
     */
    public function testBuyRefund(): void
    {
        $fisc_result = $this->newReceipt()->buyRefund(new Fiscalizer());
        $this->assertTrue($fisc_result->isSuccessful());
        $this->assertSame('wait', $fisc_result->getContent()->status);
        //self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует регистрацию документа возврата прихода
     *
     * @return void
     * @covers \AtolOnline\Entities\Correction::buyCorrect
     * @covers \AtolOnline\Api\Fiscalizer::buyCorrect
     * @covers \AtolOnline\Api\Fiscalizer::getFullUrl
     * @covers \AtolOnline\Api\Fiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\Fiscalizer::registerDocument
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws NegativePaymentSumException
     * @throws TooHighPaymentSumException
     * @throws TooLongPaymentAddressException
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     */
    public function testBuyCorrect(): void
    {
        $fisc_result = $this->newCorrection()->buyCorrect(new Fiscalizer());
        $this->assertTrue($fisc_result->isSuccessful());
        $this->assertSame('wait', $fisc_result->getContent()->status);
        //self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует разовое получение статуса фискализации документа
     *
     * @return void
     * @covers  \AtolOnline\Api\Fiscalizer::getDocumentStatus
     * @depends testSell
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidUuidException
     */
    public function testGetDocumentStatus(): void
    {
        $fisc_status = (new Fiscalizer())->getDocumentStatus(array_shift(self::$registered_uuids));
        //$this->assertTrue($fisc_status->isSuccessful());
        $this->assertTrue(in_array($fisc_status->getContent()->status, ['wait', 'done']));
    }

    /**
     * Тестирует опрос API на получение статуса фискализации документа
     *
     * @return void
     * @covers  \AtolOnline\Api\Fiscalizer::pollDocumentStatus
     * @depends testSellRefund
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidUuidException
     */
    public function testPollDocumentStatus(): void
    {
        $fisc_status = (new Fiscalizer())->pollDocumentStatus(array_shift(self::$registered_uuids));
        //$this->assertTrue($fisc_status->isSuccessful());
        $this->assertSame('done', $fisc_status->getContent()->status);
    }
}
