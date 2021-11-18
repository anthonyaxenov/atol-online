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
    Exceptions\EmptyKktLoginException,
    Exceptions\EmptyKktPasswordException,
    Exceptions\InvalidCallbackUrlException,
    Exceptions\InvalidDocumentTypeException,
    Exceptions\InvalidInnLengthException,
    Exceptions\InvalidUuidException,
    Exceptions\TooLongCallbackUrlException,
    Exceptions\TooLongKktLoginException,
    Exceptions\TooLongKktPasswordException,
    Exceptions\TooLongPaymentAddressException,
    Exceptions\TooManyItemsException,
    Exceptions\TooManyVatsException,
    TestEnvParams};
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Ramsey\Uuid\Uuid;

/**
 * Класс для отправки запросов на ККТ
 *
 * @package AtolOnline\Api
 */
class Kkt extends Client
{
    /**
     * @var bool Флаг тестового режима работы
     */
    protected bool $is_test_mode = false;
    
    /**
     * @var array Настройки доступа к ККТ
     */
    protected array $kkt_config = [];

    /**
     * @var KktResponse|null Последний ответ сервера АТОЛ
     */
    protected ?KktResponse $last_response;
    
    /**
     * @var string|null Токен авторизации
     */
    private ?string $auth_token;

    /**
     * Kkt constructor.
     *
     * @param string|null $group
     * @param string|null $login
     * @param string|null $pass
     * @param bool $test_mode Флаг тестового режима
     * @param array $guzzle_config Конфигурация GuzzleHttp
     * @throws EmptyKktLoginException Логин ККТ не может быть пустым
     * @throws TooLongKktLoginException Слишком длинный логин ККТ
     * @throws EmptyKktPasswordException Пароль ККТ не может быть пустым
     * @throws TooLongKktPasswordException Слишком длинный пароль ККТ
     * @see https://guzzle.readthedocs.io/en/latest/request-options.html
     */
    public function __construct(
        ?string $group = null,
        ?string $login = null,
        ?string $pass = null,
        bool $test_mode = false,
        array $guzzle_config = []
    ) {
        $this->resetKktConfig();
        if ($group) {
            $this->setGroup($group);
        }
        if ($login) {
            $this->setLogin($login);
        }
        if ($login) {
            $this->setPassword($pass);
        }
        $this->setTestMode($test_mode);
        $guzzle_config['base_uri'] = $this->getEndpoint();
        $guzzle_config['http_errors'] = $guzzle_config['http_errors'] ?? false;
        parent::__construct($guzzle_config);
    }
    
    /**
     * Устанавливает группу доступа к ККТ
     *
     * @param string $group
     * @return $this
     */
    public function setGroup(string $group): Kkt
    {
        $this->kkt_config['prod']['group'] = $group;
        return $this;
    }
    
    /**
     * Возвращает группу доступа к ККТ в соответствии с флагом тестового режима
     *
     * @return string
     */
    public function getGroup(): string
    {
        return $this->kkt_config[$this->isTestMode() ? 'test' : 'prod']['group'];
    }

    /**
     * Устанавливает логин доступа к ККТ
     *
     * @param string $login
     * @return $this
     * @throws EmptyKktLoginException Логин ККТ не может быть пустым
     * @throws TooLongKktLoginException Слишком длинный логин ККТ
     */
    public function setLogin(string $login): Kkt
    {
        if (empty($login)) {
            throw new EmptyKktLoginException();
        } elseif (mb_strlen($login) > Constraints::MAX_LENGTH_LOGIN) {
            throw new TooLongKktLoginException($login, Constraints::MAX_LENGTH_LOGIN);
        }
        $this->kkt_config['prod']['login'] = $login;
        return $this;
    }
    
    /**
     * Возвращает логин доступа к ККТ в соответствии с флагом тестового режима
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->kkt_config[$this->isTestMode() ? 'test' : 'prod']['login'];
    }

    /**
     * Устанавливает пароль доступа к ККТ
     *
     * @param string $password
     * @return $this
     * @throws EmptyKktPasswordException Пароль ККТ не может быть пустым
     * @throws TooLongKktPasswordException Слишком длинный пароль ККТ
     */
    public function setPassword(string $password): Kkt
    {
        if (empty($password)) {
            throw new EmptyKktPasswordException();
        } elseif (mb_strlen($password) > Constraints::MAX_LENGTH_PASSWORD) {
            throw new TooLongKktPasswordException($password, Constraints::MAX_LENGTH_PASSWORD);
        }
        $this->kkt_config['prod']['pass'] = $password;
        return $this;
    }
    
    /**
     * Возвращает логин ККТ в соответствии с флагом тестового режима
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->kkt_config[$this->isTestMode() ? 'test' : 'prod']['pass'];
    }

    /**
     * Устанавливает URL для приёма колбеков
     *
     * @param string $url
     * @return $this
     * @throws TooLongCallbackUrlException Слишком длинный Callback URL
     * @throws InvalidCallbackUrlException Невалидный Callback URL
     */
    public function setCallbackUrl(string $url): Kkt
    {
        if (mb_strlen($url) > Constraints::MAX_LENGTH_CALLBACK_URL) {
            throw new TooLongCallbackUrlException($url, Constraints::MAX_LENGTH_CALLBACK_URL);
        } elseif (!preg_match(Constraints::PATTERN_CALLBACK_URL, $url)) {
            throw new InvalidCallbackUrlException('Callback URL not matches with pattern');
        }
        $this->kkt_config[$this->isTestMode() ? 'test' : 'prod']['callback_url'] = $url;
        return $this;
    }
    
    /**
     * Возвращает URL для приёма колбеков
     *
     * @return string
     */
    public function getCallbackUrl(): string
    {
        return $this->kkt_config[$this->isTestMode() ? 'test' : 'prod']['callback_url'];
    }

    /**
     * Возвращает последний ответ сервера
     *
     * @return KktResponse|null
     */
    public function getLastResponse(): ?KktResponse
    {
        return $this->last_response;
    }
    
    /**
     * Возвращает флаг тестового режима
     *
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->is_test_mode;
    }
    
    /**
     * Устанавливает флаг тестового режима
     *
     * @param bool $test_mode
     * @return $this
     */
    public function setTestMode(bool $test_mode = true): Kkt
    {
        $this->is_test_mode = $test_mode;
        return $this;
    }

    /**
     * Регистрирует документ прихода
     *
     * @param Document $document Объект документа
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return KktResponse
     * @throws AuthFailedException Ошибка авторизации
     * @throws EmptyCorrectionInfoException В документе есть данные коррекции
     * @throws InvalidInnLengthException Некорректная длина ИНН
     * @throws TooLongPaymentAddressException Слишком длинный адрес места расчётов
     * @throws InvalidDocumentTypeException Некорректный тип документа
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
     * @throws AuthFailedException Ошибка авторизации
     * @throws EmptyCorrectionInfoException В документе есть данные коррекции
     * @throws InvalidInnLengthException Некорректная длина ИНН
     * @throws TooLongPaymentAddressException Слишком длинный адрес места расчётов
     * @throws TooManyVatsException Слишком много ставок НДС
     * @throws InvalidDocumentTypeException Некорректный тип документа
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
     * @throws AuthFailedException Ошибка авторизации
     * @throws EmptyCorrectionInfoException В документе отсутствуют данные коррекции
     * @throws InvalidInnLengthException Некорректная длина ИНН
     * @throws TooLongPaymentAddressException Слишком длинный адрес места расчётов
     * @throws TooManyItemsException Слишком много предметов расчёта
     * @throws InvalidDocumentTypeException Некорректный тип документа
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
     * @throws AuthFailedException Ошибка авторизации
     * @throws EmptyCorrectionInfoException В документе есть данные коррекции
     * @throws InvalidInnLengthException Некорректная длина ИНН
     * @throws TooLongPaymentAddressException Слишком длинный адрес места расчётов
     * @throws InvalidDocumentTypeException Некорректный тип документа
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
     * @throws AuthFailedException Ошибка авторизации
     * @throws EmptyCorrectionInfoException В документе есть данные коррекции
     * @throws InvalidInnLengthException Некорректная длина ИНН
     * @throws TooLongPaymentAddressException Слишком длинный адрес места расчётов
     * @throws TooManyVatsException Слишком много ставок НДС
     * @throws InvalidDocumentTypeException Некорректный тип документа
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
     * @throws AuthFailedException Ошибка авторизации
     * @throws InvalidUuidException Некорректный UUID документа
     * @throws GuzzleException
     */
    public function getDocumentStatus(string $uuid): KktResponse
    {
        $uuid = trim($uuid);
        if (!Uuid::isValid($uuid)) {
            throw new InvalidUuidException($uuid);
        }
        $this->auth();
        return $this->sendAtolRequest('GET', 'report/' . $uuid);
    }

    /**
     * Проверяет статус чека на ККТ нужное количество раз с указанным интервалом.
     * Вернёт результат как только при очередной проверке сменится статус регистрации документа.
     *
     * @param string $uuid UUID регистрации
     * @param int $retry_count Количество попыток
     * @param int $timeout Таймаут в секундах между попытками
     * @return KktResponse
     * @throws AuthFailedException Ошибка авторизации
     * @throws InvalidUuidException Некорректный UUID документа
     * @throws GuzzleException
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
     * Возвращает текущий токен авторизации
     *
     * @return string
     */
    public function getAuthToken(): ?string
    {
        return $this->auth_token;
    }
    
    /**
     * Устанавливает заранее известный токен авторизации
     *
     * @param string|null $auth_token
     * @return $this
     */
    public function setAuthToken(?string $auth_token): Kkt
    {
        $this->auth_token = $auth_token;
        return $this;
    }
    
    /**
     * Сбрасывает настройки ККТ по умолчанию
     */
    protected function resetKktConfig(): void
    {
        $this->kkt_config['prod']['group'] = '';
        $this->kkt_config['prod']['login'] = '';
        $this->kkt_config['prod']['pass'] = '';
        $this->kkt_config['prod']['url'] = 'https://online.atol.ru/possystem/v4';
        $this->kkt_config['prod']['callback_url'] = '';
        $this->kkt_config['test']['group'] = TestEnvParams::FFD105()['group'];
        $this->kkt_config['test']['login'] = TestEnvParams::FFD105()['login'];
        $this->kkt_config['test']['pass'] = TestEnvParams::FFD105()['password'];
        $this->kkt_config['test']['url'] = 'https://testonline.atol.ru/possystem/v4';
        $this->kkt_config['test']['callback_url'] = '';
    }
    
    /**
     * Возвращает набор заголовков для HTTP-запроса
     *
     * @return array
     */
    protected function getHeaders(): array
    {
        $headers['Content-type'] = 'application/json; charset=utf-8';
        if ($this->getAuthToken()) {
            $headers['Token'] = $this->getAuthToken();
        }
        return $headers;
    }
    
    /**
     * Возвращает адрес сервера в соответствии с флагом тестового режима
     *
     * @return string
     */
    protected function getEndpoint(): string
    {
        return $this->kkt_config[$this->isTestMode() ? 'test' : 'prod']['url'];
    }
    
    /**
     * Возвращает полный URL до метода API
     *
     * @param string     $to_method
     * @param array|null $get_parameters
     * @return string
     */
    protected function makeUrl(string $to_method, array $get_parameters = null): string
    {
        $url = $this->getEndpoint() . ($this->getAuthToken() ? '/' . $this->getGroup() : '') . '/' . $to_method;
        if ($get_parameters && is_array($get_parameters)) {
            $url .= '?' . http_build_query($get_parameters);
        }
        return $url;
    }

    /**
     * Делает запрос, возвращает декодированный ответ
     *
     * @param string $http_method Метод HTTP (GET, POST и пр)
     * @param string $api_method Метод API
     * @param mixed $data Данные для передачи
     * @param array|null $options Параметры Guzzle
     * @return KktResponse
     * @throws GuzzleException
     * @see https://guzzle.readthedocs.io/en/latest/request-options.html
     */
    protected function sendAtolRequest(
        string $http_method,
        string $api_method,
        $data = null,
        array $options = null
    ): KktResponse {
        $http_method = strtoupper($http_method);
        $options['headers'] = $this->getHeaders();
        $url = $http_method == 'GET'
            ? $this->makeUrl($api_method, $data)
            : $this->makeUrl($api_method, ['token' => $this->getAuthToken()]);
        if ($http_method != 'GET') {
            $options['json'] = $data;
        }
        $response = $this->request($http_method, $url, $options);
        return $this->last_response = new KktResponse($response);
    }

    /**
     * Производит авторизацию на ККТ и получает токен доступа для дальнейших HTTP-запросов
     *
     * @return bool
     * @throws AuthFailedException Ошибка авторизации
     * @throws GuzzleException
     */
    protected function auth(): bool
    {
        if (!$this->getAuthToken()) {
            $result = $this->sendAtolRequest('GET', 'getToken', [
                'login' => $this->getLogin(),
                'pass' => $this->getPassword(),
            ]);
            if (!$result->isValid() || !$result->getContent()->token) {
                throw new AuthFailedException($result);
            }
            $this->auth_token = $result->getContent()->token;
        }
        return true;
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
            $document->setCompany(($document->getCompany() ?: new Company())
                ->setInn(TestEnvParams::FFD105()['inn'])
                ->setSno(TestEnvParams::FFD105()['sno'])
                ->setPaymentAddress(TestEnvParams::FFD105()['payment_address']));
        }
        $data['timestamp'] = date('d.m.y H:i:s');
        $data['external_id'] = $external_id ?: Uuid::uuid4()->toString();
        $data[$type] = $document;
        if ($this->getCallbackUrl()) {
            $data['service'] = ['callback_url' => $this->getCallbackUrl()];
        }
        return $this->sendAtolRequest('POST', trim($api_method), $data);
    }
}
