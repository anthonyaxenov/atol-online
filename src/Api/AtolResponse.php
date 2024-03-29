<?php

/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace AtolOnline\Api;

use JetBrains\PhpStorm\{
    ArrayShape,
    Pure};
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;
use Stringable;

/**
 * Класс AtolResponse, описывающий ответ от ККТ
 *
 * @property mixed $error
 * @package AtolOnline\Api
 */
final class AtolResponse implements JsonSerializable, Stringable
{
    /**
     * @var int Код ответа сервера
     */
    protected int $code;

    /**
     * @var object|array|null Содержимое ответа сервера
     */
    protected object | array | null $content;

    /**
     * @var array Заголовки ответа
     */
    protected array $headers;

    /**
     * AtolResponse constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->code = $response->getStatusCode();
        $this->headers = $response->getHeaders();
        $this->content = json_decode((string)$response->getBody());
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
    #[Pure]
    public function __get($name): mixed
    {
        return $this->getContent()?->$name;
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
     * @return mixed
     */
    public function getContent(): mixed
    {
        return $this->content;
    }

    /**
     * Проверяет успешность запроса по соержимому результата
     *
     * @return bool
     */
    #[Pure]
    public function isSuccessful(): bool
    {
        return !empty($this->getCode())
            && !empty($this->getContent())
            && empty($this->getContent()->error)
            && $this->getCode() < 400;
    }

    /**
     * Возвращает текстовое представление
     */
    public function __toString(): string
    {
        return json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape(
        [
            'code' => 'int',
            'headers' => 'array|\string[][]',
            'body' => 'mixed',
        ]
    )]
    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'headers' => $this->headers,
            'body' => $this->content,
        ];
    }
}
