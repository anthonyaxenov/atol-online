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
    Constants\Constraints,
    Helpers,
    TestEnvParams,
    Tests\BasicTestCase};
use AtolOnline\Api\{
    AtolClient,
    KktFiscalizer};
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
    TooLongLoginException,
    TooLongPasswordException,
    TooLongPaymentAddressException,
    TooManyException};
use GuzzleHttp\Exception\GuzzleException;

/**
 * Набор тестов для проверки работы фискализатора
 */
class KktFiscalizerTest extends BasicTestCase
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
     * @covers \AtolOnline\Api\KktFiscalizer
     */
    public function testConstructorWithourArgs(): void
    {
        $fisc = new KktFiscalizer();
        $this->assertIsObject($fisc);
        $this->assertIsSameClass(KktFiscalizer::class, $fisc);
        $this->assertExtendsClasses([AtolClient::class], $fisc);
    }

    /**
     * Тестирует установку и возврат группы ККТ
     *
     * @return void
     * @covers \AtolOnline\Api\KktFiscalizer
     * @covers \AtolOnline\Api\KktFiscalizer::getGroup
     * @covers \AtolOnline\Api\KktFiscalizer::setGroup
     */
    public function testGroup(): void
    {
        // test mode
        $this->assertEquals(
            TestEnvParams::FFD105()['group'],
            (new KktFiscalizer(group: 'group'))->getGroup()
        );
        // prod mode
        $this->assertEquals('group', (new KktFiscalizer(false, group: 'group'))->getGroup());
        $this->assertNull((new KktFiscalizer(false))->getGroup());
    }

    /**
     * Тестирует выброс исключения при попытке передать пустую группу ККТ в конструктор
     *
     * @return void
     * @covers \AtolOnline\Api\KktFiscalizer
     * @covers \AtolOnline\Api\KktFiscalizer::setGroup
     * @covers \AtolOnline\Exceptions\EmptyGroupException
     */
    public function testEmptyGroupException(): void
    {
        $this->expectException(EmptyGroupException::class);
        new KktFiscalizer(group: "\n\r \0\t");
    }

    /**
     * Тестирует выброс исключения при попытке установить слишком длинный адрес колбека
     *
     * @return void
     * @covers \AtolOnline\Api\KktFiscalizer::setCallbackUrl
     * @covers \AtolOnline\Exceptions\TooLongCallbackUrlException
     * @throws InvalidCallbackUrlException
     * @throws TooLongCallbackUrlException
     */
    public function testTooLongCallbackUrlException(): void
    {
        $this->expectException(TooLongCallbackUrlException::class);
        (new KktFiscalizer())->setCallbackUrl(Helpers::randomStr(Constraints::MAX_LENGTH_CALLBACK_URL + 1));
    }

    /**
     * Тестирует выброс исключения при попытке установить слишком длинный адрес колбека
     *
     * @return void
     * @covers \AtolOnline\Api\KktFiscalizer::setCallbackUrl
     * @covers \AtolOnline\Exceptions\InvalidCallbackUrlException
     * @throws InvalidCallbackUrlException
     * @throws TooLongCallbackUrlException
     */
    public function testInvalidCallbackUrlException(): void
    {
        $this->expectException(InvalidCallbackUrlException::class);
        (new KktFiscalizer())->setCallbackUrl(Helpers::randomStr());
    }

    /**
     * Тестирует обнуление адреса колбека
     *
     * @param mixed $param
     * @return void
     * @covers       \AtolOnline\Api\KktFiscalizer::setCallbackUrl
     * @covers       \AtolOnline\Api\KktFiscalizer::getCallbackUrl
     * @dataProvider providerNullableStrings
     * @throws InvalidCallbackUrlException
     * @throws TooLongCallbackUrlException
     */
    public function testNullableCallbackUrl(mixed $param): void
    {
        $this->assertNull((new KktFiscalizer())->setCallbackUrl($param)->getCallbackUrl());
    }

    /**
     * Тестирует регистрацию документа прихода
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt::sell
     * @covers \AtolOnline\Api\KktFiscalizer::sell
     * @covers \AtolOnline\Api\KktFiscalizer::getFullUrl
     * @covers \AtolOnline\Api\KktFiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::registerDocument
     * @throws AuthFailedException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     * @throws TooLongPaymentAddressException
     * @throws TooManyException
     * @throws GuzzleException
     */
    public function testSell(): void
    {
        $fisc_result = $this->newReceipt()->sell(new KktFiscalizer());
        $this->assertTrue($fisc_result->isValid());
        $this->assertEquals('wait', $fisc_result->getContent()->status);
        self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует регистрацию документа возврата прихода
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt::sellRefund
     * @covers \AtolOnline\Api\KktFiscalizer::sellRefund
     * @covers \AtolOnline\Api\KktFiscalizer::getFullUrl
     * @covers \AtolOnline\Api\KktFiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::registerDocument
     * @throws AuthFailedException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     * @throws TooLongPaymentAddressException
     * @throws TooManyException
     * @throws GuzzleException
     */
    public function testSellRefund(): void
    {
        $fisc_result = $this->newReceipt()->sellRefund(new KktFiscalizer());
        $this->assertTrue($fisc_result->isValid());
        $this->assertEquals('wait', $fisc_result->getContent()->status);
        self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует регистрацию документа возврата прихода
     *
     * @return void
     * @covers \AtolOnline\Entities\Correction::sellCorrect
     * @covers \AtolOnline\Api\KktFiscalizer::sellCorrect
     * @covers \AtolOnline\Api\KktFiscalizer::getFullUrl
     * @covers \AtolOnline\Api\KktFiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::registerDocument
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
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     * @throws TooLongPaymentAddressException
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     */
    public function testSellCorrect(): void
    {
        $fisc_result = $this->newCorrection()->sellCorrect(new KktFiscalizer());
        $this->assertTrue($fisc_result->isValid());
        $this->assertEquals('wait', $fisc_result->getContent()->status);
        self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует регистрацию документа расхода
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt::buy
     * @covers \AtolOnline\Api\KktFiscalizer::buy
     * @covers \AtolOnline\Api\KktFiscalizer::getFullUrl
     * @covers \AtolOnline\Api\KktFiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::registerDocument
     * @throws AuthFailedException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     * @throws TooLongPaymentAddressException
     * @throws TooManyException
     * @throws GuzzleException
     */
    public function testBuy(): void
    {
        $fisc_result = $this->newReceipt()->buy(new KktFiscalizer());
        $this->assertTrue($fisc_result->isValid());
        $this->assertEquals('wait', $fisc_result->getContent()->status);
        self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует регистрацию документа возврата расхода
     *
     * @return void
     * @covers \AtolOnline\Entities\Receipt::buyRefund
     * @covers \AtolOnline\Api\KktFiscalizer::buyRefund
     * @covers \AtolOnline\Api\KktFiscalizer::getFullUrl
     * @covers \AtolOnline\Api\KktFiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::registerDocument
     * @throws AuthFailedException
     * @throws EmptyItemNameException
     * @throws EmptyItemsException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws InvalidEntityInCollectionException
     * @throws InvalidEnumValueException
     * @throws InvalidInnLengthException
     * @throws InvalidPaymentAddressException
     * @throws NegativeItemPriceException
     * @throws NegativeItemQuantityException
     * @throws NegativePaymentSumException
     * @throws TooHighItemPriceException
     * @throws TooHighPaymentSumException
     * @throws TooLongItemNameException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     * @throws TooLongPaymentAddressException
     * @throws TooManyException
     * @throws GuzzleException
     */
    public function testBuyRefund(): void
    {
        $fisc_result = $this->newReceipt()->buyRefund(new KktFiscalizer());
        $this->assertTrue($fisc_result->isValid());
        $this->assertEquals('wait', $fisc_result->getContent()->status);
        self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует регистрацию документа возврата прихода
     *
     * @return void
     * @covers \AtolOnline\Entities\Correction::buyCorrect
     * @covers \AtolOnline\Api\KktFiscalizer::buyCorrect
     * @covers \AtolOnline\Api\KktFiscalizer::getFullUrl
     * @covers \AtolOnline\Api\KktFiscalizer::getAuthEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::getMainEndpoint
     * @covers \AtolOnline\Api\KktFiscalizer::registerDocument
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
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     * @throws TooLongPaymentAddressException
     * @throws EmptyCorrectionNumberException
     * @throws InvalidCorrectionDateException
     */
    public function testBuyCorrect(): void
    {
        $fisc_result = $this->newCorrection()->buyCorrect(new KktFiscalizer());
        $this->assertTrue($fisc_result->isValid());
        $this->assertEquals('wait', $fisc_result->getContent()->status);
        self::$registered_uuids[] = $fisc_result->getContent()->uuid;
    }

    /**
     * Тестирует разовое получение статуса фискализации документа
     *
     * @return void
     * @covers \AtolOnline\Api\KktFiscalizer::getDocumentStatus
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidUuidException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testGetDocumentStatus(): void
    {
        $fisc_status = (new KktFiscalizer())->getDocumentStatus(self::$registered_uuids[0]);
        $this->assertTrue($fisc_status->isValid());
        $this->assertTrue(in_array($fisc_status->getContent()->status, ['wait', 'done']));
    }

    /**
     * Тестирует опрос API на получение статуса фискализации документа
     *
     * @return void
     * @covers \AtolOnline\Api\KktFiscalizer::pollDocumentStatus
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidUuidException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function testPollDocumentStatus(): void
    {
        $fisc_status = (new KktFiscalizer())->pollDocumentStatus(self::$registered_uuids[1]);
        $this->assertTrue($fisc_status->isValid());
        $this->assertEquals('done', $fisc_status->getContent()->status);
    }

}
