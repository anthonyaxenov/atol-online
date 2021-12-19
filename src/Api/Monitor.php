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

use AtolOnline\Entities\Kkt;
use AtolOnline\Exceptions\{
    AuthFailedException,
    EmptyLoginException,
    EmptyMonitorDataException,
    EmptyPasswordException,
    NotEnoughMonitorDataException};
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

/**
 * Класс для мониторинга ККТ
 *
 * @see https://online.atol.ru/files/API_service_information.pdf Документация
 */
class Monitor extends AtolClient
{
    /**
     * @inheritDoc
     */
    #[Pure]
    protected function getAuthEndpoint(): string
    {
        return $this->isTestMode()
            ? 'https://testonline.atol.ru/api/auth/v1/gettoken'
            : 'https://online.atol.ru/api/auth/v1/gettoken';
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    protected function getMainEndpoint(): string
    {
        return $this->isTestMode()
            ? 'https://testonline.atol.ru/api/kkt/v1'
            : 'https://online.atol.ru/api/kkt/v1';
    }

    /**
     * Получает от API информацию обо всех ККТ и ФН в рамках группы
     *
     * @param int|null $limit
     * @param int|null $offset
     * @return AtolResponse|null
     * @throws GuzzleException
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @see https://online.atol.ru/files/API_service_information.pdf Документация, стр 9
     */
    protected function fetchAll(?int $limit = null, ?int $offset = null): ?AtolResponse
    {
        $params = [];
        !is_null($limit) && $params['limit'] = $limit;
        !is_null($offset) && $params['offset'] = $offset;
        return $this->auth()
            ? $this->sendRequest('GET', self::getUrlToMethod('cash-registers'), $params)
            : null;
    }

    /**
     * Возвращает информацию обо всех ККТ и ФН в рамках группы
     *
     * @param int|null $limit
     * @param int|null $offset
     * @return Collection
     * @throws AuthFailedException
     * @throws EmptyLoginException
     * @throws EmptyPasswordException
     * @throws GuzzleException
     * @see https://online.atol.ru/files/API_service_information.pdf Документация, стр 9
     */
    public function getAll(?int $limit = null, ?int $offset = null): Collection
    {
        $collection = collect($this->fetchAll($limit, $offset)->getContent());
        return $collection->map(fn($data) => new Kkt($data));
    }

    /**
     * Получает от API информацию о конкретной ККТ по её серийному номеру
     *
     * @param string $serial_number
     * @return AtolResponse
     * @throws GuzzleException
     * @see https://online.atol.ru/files/API_service_information.pdf Документация, стр 11
     */
    protected function fetchOne(string $serial_number): AtolResponse
    {
        return $this->sendRequest(
            'GET',
            self::getUrlToMethod('cash-registers') . '/' . trim($serial_number),
            options: [
                'headers' => [
                    'Accept' => 'application/hal+json',
                ],
            ]
        );
    }

    /**
     * Возвращает информацию о конкретной ККТ по её серийному номеру
     *
     * @param string $serial_number
     * @return Kkt
     * @throws GuzzleException
     * @throws EmptyMonitorDataException
     * @throws NotEnoughMonitorDataException
     * @see https://online.atol.ru/files/API_service_information.pdf Документация, стр 11
     */
    public function getOne(string $serial_number): Kkt
    {
        return new Kkt($this->fetchOne($serial_number)->getContent()->data);
    }
}
