<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Api;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;
use stdClass;

/**
 * Класс AtolResponse, описывающий ответ от ККТ
 *
 * @package AtolOnline\Api
 */
class KktResponse implements JsonSerializable
{
    /**
     * @var int Код ответа сервера
     */
    protected $code;
    
    /**
     * @var \stdClass Содержимое ответа сервера
     */
    protected $content;
    
    /**
     * @var array Заголовки ответа
     */
    protected $headers;
    
    /**
     * AtolResponse constructor.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->code = $response->getStatusCode();
        $this->headers = $response->getHeaders();
        $this->content = json_decode($response->getBody());
    }
    
    /**
     * Возвращает заголовки ответа
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    /**
     * Возвращает запрошенный параметр из декодированного объекта результата
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getContent()->$name;
    }
    
    /**
     * Возвращает код ответа
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }
    
    /**
     * Возвращает объект результата запроса
     *
     * @return stdClass|null
     */
    public function getContent(): ?stdClass
    {
        return $this->content;
    }
    
    /**
     * Проверяет успешность запроса по соержимому результата
     *
     * @return bool
     */
    public function isValid()
    {
        return !empty($this->getCode())
            && !empty($this->getContent())
            && empty($this->getContent()->error)
            && (int)$this->getCode() < 400;
    }
    
    /**
     * Возвращает текстовое представление
     */
    public function __toString()
    {
        return json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'code' => $this->code,
            'headers' => $this->headers,
            'body' => $this->content,
        ];
    }
}