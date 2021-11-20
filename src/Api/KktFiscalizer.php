<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Api;

use AtolOnline\{
    Constants\Constraints,
    Entities\Company,
    Entities\Document,
    Exceptions\AuthFailedException,
    Exceptions\EmptyCorrectionInfoException,
    Exceptions\EmptyLoginException,
    Exceptions\EmptyPasswordException,
    Exceptions\InvalidCallbackUrlException,
    Exceptions\InvalidDocumentTypeException,
    Exceptions\InvalidInnLengthException,
    Exceptions\InvalidUuidException,
    Exceptions\TooLongCallbackUrlException,
    Exceptions\TooLongLoginException,
    Exceptions\TooLongPasswordException,
    Exceptions\TooLongPaymentAddressException,
    Exceptions\TooManyItemsException,
    Exceptions\TooManyVatsException,
    TestEnvParams
};
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Ramsey\Uuid\Uuid;

/**
 * Класс для регистрации документов на ККТ
 */
class KktFiscalizer extends AtolClient
{
    /**
     * @var string|null Группа ККТ
     */
    private ?string $group = null;

    /**
     * @var string|null URL для приёма POST-запроса от API АТОЛ с результатом регистрации документа
     */
    private ?string $callback_url = null;

    /**
     * Конструктор
     *
     * @param bool $test_mode
     * @param string|null $login
     * @param string|null $password
     * @param string|null $group
     * @param array $config
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     * @see https://guzzle.readthedocs.io/en/latest/request-options.html
     */
    public function __construct(
        bool $test_mode = true,
        ?string $login = null,
        ?string $password = null,
        ?string $group = null,
        array $config = []
    ) {
        parent::__construct($test_mode, $login, $password, $config);
        !is_null($group) && $this->setGroup($group);
    }

    /**
     * Устанавливает группу доступа к ККТ
     *
     * @param string $group
     * @return $this
     */
    public function setGroup(string $group): self
    {
        // критерии к длине строки не описаны ни в схеме, ни в документации
        $this->group = $group;
        return $this;
    }

    /**
     * Возвращает группу доступа к ККТ в соответствии с флагом тестового режима
     *
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * Устанавливает URL для приёма колбеков
     *
     * @param string $url
     * @return $this
     * @throws TooLongCallbackUrlException
     * @throws InvalidCallbackUrlException
     */
    public function setCallbackUrl(string $url): self
    {
        if (mb_strlen($url) > Constraints::MAX_LENGTH_CALLBACK_URL) {
            throw new TooLongCallbackUrlException($url, Constraints::MAX_LENGTH_CALLBACK_URL);
        } elseif (!preg_match(Constraints::PATTERN_CALLBACK_URL, $url)) {
            throw new InvalidCallbackUrlException('Callback URL not matches with pattern');
        }
        $this->callback_url = $url;
        return $this;
    }

    /**
     * Возвращает URL для приёма колбеков
     *
     * @return string
     */
    public function getCallbackUrl(): string
    {
        return $this->callback_url;
    }

    /**
     * Регистрирует документ прихода
     *
     * @param Document $document Объект документа
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return KktResponse
     * @throws AuthFailedException
     * @throws EmptyCorrectionInfoException
     * @throws InvalidInnLengthException
     * @throws TooLongPaymentAddressException
     * @throws InvalidDocumentTypeException
     * @throws GuzzleException
     */
    public function sell(Document $document, ?string $external_id = null): KktResponse
    {
        if ($document->getCorrectionInfo()) {
            throw new EmptyCorrectionInfoException('Некорректная операция над документом коррекции');
        }
        return $this->registerDocument('sell', 'receipt', $document, $external_id);
    }

    /**
     * Регистрирует документ возврата прихода
     *
     * @param Document $document Объект документа
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return KktResponse
     * @throws AuthFailedException
     * @throws EmptyCorrectionInfoException
     * @throws InvalidInnLengthException
     * @throws TooLongPaymentAddressException
     * @throws TooManyVatsException
     * @throws InvalidDocumentTypeException
     * @throws GuzzleException
     */
    public function sellRefund(Document $document, ?string $external_id = null): KktResponse
    {
        if ($document->getCorrectionInfo()) {
            throw new EmptyCorrectionInfoException('Invalid operation on correction document');
        }
        return $this->registerDocument('sell_refund', 'receipt', $document->clearVats(), $external_id);
    }

    /**
     * Регистрирует документ коррекции прихода
     *
     * @param Document $document Объект документа
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return KktResponse
     * @throws AuthFailedException
     * @throws EmptyCorrectionInfoException
     * @throws InvalidInnLengthException
     * @throws TooLongPaymentAddressException
     * @throws TooManyItemsException
     * @throws InvalidDocumentTypeException
     * @throws GuzzleException
     */
    public function sellCorrection(Document $document, ?string $external_id = null): KktResponse
    {
        if (!$document->getCorrectionInfo()) {
            throw new EmptyCorrectionInfoException();
        }
        $document->setClient(null)->setItems([]);
        return $this->registerDocument('sell_correction', 'correction', $document, $external_id);
    }

    /**
     * Регистрирует документ расхода
     *
     * @param Document $document
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return KktResponse
     * @throws AuthFailedException
     * @throws EmptyCorrectionInfoException
     * @throws InvalidInnLengthException
     * @throws TooLongPaymentAddressException
     * @throws InvalidDocumentTypeException
     * @throws GuzzleException
     */
    public function buy(Document $document, ?string $external_id = null): KktResponse
    {
        if ($document->getCorrectionInfo()) {
            throw new EmptyCorrectionInfoException('Invalid operation on correction document');
        }
        return $this->registerDocument('buy', 'receipt', $document, $external_id);
    }

    /**
     * Регистрирует документ возврата расхода
     *
     * @param Document $document
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return KktResponse
     * @throws AuthFailedException
     * @throws EmptyCorrectionInfoException
     * @throws InvalidInnLengthException
     * @throws TooLongPaymentAddressException
     * @throws TooManyVatsException
     * @throws InvalidDocumentTypeException
     * @throws GuzzleException
     */
    public function buyRefund(Document $document, ?string $external_id = null): KktResponse
    {
        if ($document->getCorrectionInfo()) {
            throw new EmptyCorrectionInfoException('Invalid operation on correction document');
        }
        return $this->registerDocument('buy_refund', 'receipt', $document->clearVats(), $external_id);
    }

    /**
     * Регистрирует документ коррекции расхода
     *
     * @param Document $document
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return KktResponse
     * @throws AuthFailedException Ошибка авторизации
     * @throws EmptyCorrectionInfoException В документе отсутствуют данные коррекции
     * @throws InvalidInnLengthException Некорректная длтина ИНН
     * @throws TooLongPaymentAddressException Слишком длинный адрес места расчётов
     * @throws TooManyItemsException Слишком много предметов расчёта
     * @throws InvalidDocumentTypeException Некорректный тип документа
     * @throws GuzzleException
     */
    public function buyCorrection(Document $document, ?string $external_id = null): KktResponse
    {
        if (!$document->getCorrectionInfo()) {
            throw new EmptyCorrectionInfoException();
        }
        $document->setClient(null)->setItems([]);
        return $this->registerDocument('buy_correction', 'correction', $document, $external_id);
    }

    /**
     * Проверяет статус чека на ККТ один раз
     *
     * @param string $uuid UUID регистрации
     * @return KktResponse
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidUuidException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function getDocumentStatus(string $uuid): KktResponse
    {
        $uuid = trim($uuid);
        if (!Uuid::isValid($uuid)) {
            throw new InvalidUuidException($uuid);
        }
        $this->auth();
        return $this->sendRequest('GET', 'report/' . $uuid);
    }

    /**
     * Проверяет статус чека на ККТ нужное количество раз с указанным интервалом.
     * Вернёт результат как только при очередной проверке сменится статус регистрации документа.
     *
     * @param string $uuid UUID регистрации
     * @param int $retry_count Количество попыток
     * @param int $timeout Таймаут в секундах между попытками
     * @return KktResponse
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @throws InvalidUuidException
     * @throws TooLongLoginException
     * @throws TooLongPasswordException
     */
    public function pollDocumentStatus(string $uuid, int $retry_count = 5, int $timeout = 1): KktResponse
    {
        $try = 0;
        do {
            $response = $this->getDocumentStatus($uuid);
            if ($response->isValid() && $response->getContent()->status == 'done') {
                break;
            } else {
                sleep($timeout);
            }
            ++$try;
        } while ($try < $retry_count);
        return $response;
    }

    /**
     * Отправляет документ на регистрацию
     *
     * @param string $api_method Метод API
     * @param string $type Тип документа: receipt, correction
     * @param Document $document Объект документа
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return KktResponse
     * @throws AuthFailedException Ошибка авторизации
     * @throws InvalidDocumentTypeException Некорректный тип документа
     * @throws InvalidInnLengthException Некорректная длина ИНН
     * @throws TooLongPaymentAddressException Слишком длинный адрес места расчётов
     * @throws GuzzleException
     * @throws Exception
     */
    protected function registerDocument(
        string $api_method,
        string $type,
        Document $document,
        ?string $external_id = null
    ): KktResponse {
        $type = trim($type);
        if (!in_array($type, ['receipt', 'correction'])) {
            throw new InvalidDocumentTypeException($type);
        }
        $this->auth();
        if ($this->isTestMode()) {
            $document->setCompany(new Company(
                'test@example.com',
                TestEnvParams::FFD105()['sno'],
                TestEnvParams::FFD105()['inn'],
                TestEnvParams::FFD105()['payment_address'],
            ));
        }
        $data['timestamp'] = date('d.m.y H:i:s');
        $data['external_id'] = $external_id ?: Uuid::uuid4()->toString();
        $data[$type] = $document;
        if ($this->getCallbackUrl()) {
            $data['service'] = ['callback_url' => $this->getCallbackUrl()];
        }
        return $this->sendRequest('POST', trim($api_method), $data);
    }

    /**
     * @inheritDoc
     */
    protected function getAuthEndpoint(): string
    {
        return $this->isTestMode()
            ? 'https://testonline.atol.ru/possystem/v1/getToken'
            : 'https://online.atol.ru/possystem/v1/getToken';
    }

    /**
     * @inheritDoc
     */
    protected function getMainEndpoint(): string
    {
        return $this->isTestMode()
            ? 'https://testonline.atol.ru/possystem/v4/'
            : 'https://online.atol.ru/possystem/v4/';
    }
}
