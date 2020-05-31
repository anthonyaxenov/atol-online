<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Api;

use AtolOnline\{Entities\Document,
    Exceptions\AtolCorrectionInfoException,
    Exceptions\AtolInvalidUuidException,
    Exceptions\AtolKktLoginEmptyException,
    Exceptions\AtolKktLoginTooLongException,
    Exceptions\AtolKktPasswordEmptyException,
    Exceptions\AtolWrongDocumentTypeException
};
use GuzzleHttp\Client;
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
    protected $is_test_mode = false;
    
    /**
     * @var array Настройки доступа к ККТ
     */
    protected $kkt_config = [];
    
    /**
     * @var \AtolOnline\Api\KktResponse|null Последний ответ сервера АТОЛ
     */
    protected $last_response;
    
    /**
     * @var string|null Токен авторизации
     */
    private $auth_token;
    
    /**
     * Kkt constructor.
     *
     * @param string|null $group
     * @param string|null $login
     * @param string|null $pass
     * @param bool        $test_mode     Флаг тестового режима
     * @param array       $guzzle_config Конфигурация GuzzleHttp
     * @throws \AtolOnline\Exceptions\AtolKktLoginEmptyException Логин ККТ не может быть пустым
     * @throws \AtolOnline\Exceptions\AtolKktLoginTooLongException Слишком длинный логин ККТ
     * @throws \AtolOnline\Exceptions\AtolKktPasswordEmptyException Пароль ККТ не может быть пустым
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
    public function setGroup(string $group)
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
     * @throws \AtolOnline\Exceptions\AtolKktLoginEmptyException Логин ККТ не может быть пустым
     * @throws \AtolOnline\Exceptions\AtolKktLoginTooLongException Слишком длинный логин ККТ
     */
    public function setLogin(string $login)
    {
        if (!$this->isTestMode()) {
            if (empty($login)) {
                throw new AtolKktLoginEmptyException();
            } elseif ((function_exists('mb_strlen') ? mb_strlen($login) : strlen($login)) > 100) {
                throw new AtolKktLoginTooLongException($login, 100);
            }
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
     * @throws \AtolOnline\Exceptions\AtolKktPasswordEmptyException Пароль ККТ не может быть пустым
     */
    public function setPassword(string $password)
    {
        if (!$this->isTestMode()) {
            if (empty($password)) {
                throw new AtolKktPasswordEmptyException();
            }
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
     */
    public function setCallbackUrl(string $url)
    {
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
     * @return mixed
     */
    public function getLastResponse()
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
    public function setTestMode(bool $test_mode = true)
    {
        $this->is_test_mode = $test_mode;
        return $this;
    }
    
    /**
     * Регистрирует документ прихода
     *
     * @param \AtolOnline\Entities\Document $document
     * @param string|null                   $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return \AtolOnline\Api\KktResponse
     * @throws \AtolOnline\Exceptions\AtolWrongDocumentTypeException Некорректный тип документа
     * @throws \AtolOnline\Exceptions\AtolCorrectionInfoException В документе есть данные коррекции
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sell(Document $document, ?string $external_id = null)
    {
        if ($document->getCorrectionInfo()) {
            throw new AtolCorrectionInfoException('Некорректная операция над документом коррекции');
        }
        return $this->registerDocument('sell', 'receipt', $document, $external_id);
    }
    
    /**
     * Регистрирует документ возврата прихода
     *
     * @param \AtolOnline\Entities\Document $document
     * @param string|null                   $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return \AtolOnline\Api\KktResponse
     * @throws \AtolOnline\Exceptions\AtolPriceTooHighException Слишком большая сумма
     * @throws \AtolOnline\Exceptions\AtolTooManyVatsException Слишком много ставок НДС
     * @throws \AtolOnline\Exceptions\AtolWrongDocumentTypeException Некорректный тип документа
     * @throws \AtolOnline\Exceptions\AtolCorrectionInfoException В документе есть данные коррекции
     */
    public function sellRefund(Document $document, ?string $external_id = null)
    {
        if ($document->getCorrectionInfo()) {
            throw new AtolCorrectionInfoException('Некорректная операция над документом коррекции');
        }
        return $this->registerDocument('sell_refund', 'receipt', $document->clearVats(), $external_id);
    }
    
    /**
     * Регистрирует документ коррекции прихода
     *
     * @param \AtolOnline\Entities\Document $document
     * @param string|null                   $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return \AtolOnline\Api\KktResponse
     * @throws \AtolOnline\Exceptions\AtolWrongDocumentTypeException Некорректный тип документа
     * @throws \AtolOnline\Exceptions\AtolCorrectionInfoException В документе отсутствуют данные коррекции
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException Слишком много предметов расчёта
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sellCorrection(Document $document, ?string $external_id = null)
    {
        if (!$document->getCorrectionInfo()) {
            throw new AtolCorrectionInfoException();
        }
        $document->setClient(null)->setItems([]);
        return $this->registerDocument('sell_correction', 'correction', $document, $external_id);
    }
    
    /**
     * Регистрирует документ расхода
     *
     * @param \AtolOnline\Entities\Document $document
     * @param string|null                   $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return \AtolOnline\Api\KktResponse
     * @throws \AtolOnline\Exceptions\AtolWrongDocumentTypeException Некорректный тип документа
     * @throws \AtolOnline\Exceptions\AtolCorrectionInfoException В документе есть данные коррекции
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function buy(Document $document, ?string $external_id = null)
    {
        if ($document->getCorrectionInfo()) {
            throw new AtolCorrectionInfoException('Некорректная операция над документом коррекции');
        }
        return $this->registerDocument('buy', 'receipt', $document, $external_id);
    }
    
    /**
     * Регистрирует документ возврата расхода
     *
     * @param \AtolOnline\Entities\Document $document
     * @param string|null                   $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return \AtolOnline\Api\KktResponse
     * @throws \AtolOnline\Exceptions\AtolPriceTooHighException Слишком большая сумма
     * @throws \AtolOnline\Exceptions\AtolTooManyVatsException Слишком много ставок НДС
     * @throws \AtolOnline\Exceptions\AtolWrongDocumentTypeException Некорректный тип документа
     * @throws \AtolOnline\Exceptions\AtolCorrectionInfoException В документе есть данные коррекции
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function buyRefund(Document $document, ?string $external_id = null)
    {
        if ($document->getCorrectionInfo()) {
            throw new AtolCorrectionInfoException('Некорректная операция над документом коррекции');
        }
        return $this->registerDocument('buy_refund', 'receipt', $document->clearVats(), $external_id);
    }
    
    /**
     * Регистрирует документ коррекции расхода
     *
     * @param Document    $document
     * @param string|null $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return \AtolOnline\Api\KktResponse
     * @throws \AtolOnline\Exceptions\AtolWrongDocumentTypeException Некорректный тип документа
     * @throws \AtolOnline\Exceptions\AtolCorrectionInfoException В документе отсутствуют данные коррекции
     * @throws \AtolOnline\Exceptions\AtolTooManyItemsException Слишком много предметов расчёта
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function buyCorrection(Document $document, ?string $external_id = null)
    {
        if (!$document->getCorrectionInfo()) {
            throw new AtolCorrectionInfoException();
        }
        $document->setClient(null)->setItems([]);
        return $this->registerDocument('buy_correction', 'correction', $document, $external_id);
    }
    
    /**
     * Проверяет статус чека на ККТ один раз
     *
     * @param string $uuid UUID регистрации
     * @return \AtolOnline\Api\KktResponse
     * @throws \AtolOnline\Exceptions\AtolInvalidUuidException Некорректный UUID документа
     */
    public function getDocumentStatus(string $uuid)
    {
        $uuid = trim($uuid);
        if (!Uuid::isValid($uuid)) {
            throw new AtolInvalidUuidException($uuid);
        }
        $this->auth();
        return $this->sendAtolRequest('GET', 'report/'.$uuid);
    }
    
    /**
     * Проверяет статус чека на ККТ нужное количество раз с указанным интервалом.
     * Вернёт результат как только при очередной проверке сменится статус регистрации документа.
     *
     * @param string $uuid        UUID регистрации
     * @param int    $retry_count Количество попыток
     * @param int    $timeout     Таймаут в секундах между попытками
     * @return \AtolOnline\Api\KktResponse
     * @throws \AtolOnline\Exceptions\AtolException Некорректный UUID документа
     */
    public function pollDocumentStatus(string $uuid, int $retry_count = 5, int $timeout = 1)
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
    public function setAuthToken(?string $auth_token)
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
        $this->kkt_config['test']['group'] = 'v4-online-atol-ru_4179';
        $this->kkt_config['test']['login'] = 'v4-online-atol-ru';
        $this->kkt_config['test']['pass'] = 'iGFFuihss';
        $this->kkt_config['test']['url'] = 'https://testonline.atol.ru/possystem/v4';
        $this->kkt_config['test']['callback_url'] = '';
    }
    
    /**
     * Возвращает набор заголовков для HTTP-запроса
     *
     * @return array
     */
    protected function getHeaders()
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
    protected function makeUrl(string $to_method, array $get_parameters = null)
    {
        $url = $this->getEndpoint().($this->getAuthToken() ? '/'.$this->getGroup() : '').'/'.$to_method;
        if ($get_parameters && is_array($get_parameters)) {
            $url .= '?'.http_build_query($get_parameters);
        }
        return $url;
    }
    
    /**
     * Делает запрос, возвращает декодированный ответ
     *
     * @param string     $http_method Метод HTTP (GET, POST и пр)
     * @param string     $api_method  Метод API
     * @param mixed      $data        Данные для передачи
     * @param array|null $options     Параметры Guzzle
     * @return \AtolOnline\Api\KktResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see https://guzzle.readthedocs.io/en/latest/request-options.html
     */
    protected function sendAtolRequest(string $http_method, string $api_method, $data = null, array $options = null)
    {
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function auth()
    {
        if (!$this->getAuthToken()) {
            $result = $this->sendAtolRequest('GET', 'getToken', [
                'login' => $this->getLogin(),
                'pass' => $this->getPassword(),
            ]);
            if (!$result->isValid() || !$result->getContent()->token) {
                return false;
            }
            $this->auth_token = $result->getContent()->token;
        }
        return true;
    }
    
    /**
     * Отправляет документ на регистрацию
     *
     * @param string                        $api_method  Метод API
     * @param string                        $type        Тип документа: receipt, correction
     * @param \AtolOnline\Entities\Document $document    Объект документа
     * @param string|null                   $external_id Уникальный код документа (если не указан, то будет создан UUID)
     * @return \AtolOnline\Api\KktResponse
     * @throws \AtolOnline\Exceptions\AtolWrongDocumentTypeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function registerDocument(string $api_method, string $type, Document $document, ?string $external_id = null)
    {
        $type = trim($type);
        if (!in_array($type, ['receipt', 'correction'])) {
            throw new AtolWrongDocumentTypeException($type);
        }
        $this->auth();
        $data['timestamp'] = date('d.m.y H:i:s');
        $data['external_id'] = $external_id ?: Uuid::uuid4()->toString();
        $data[$type] = $document;
        if ($this->getCallbackUrl()) {
            $data['service'] = ['callback_url' => $this->getCallbackUrl()];
        }
        return $this->sendAtolRequest('POST', trim($api_method), $data);
    }
}
