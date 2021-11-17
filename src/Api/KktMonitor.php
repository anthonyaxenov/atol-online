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

use GuzzleHttp\Exception\GuzzleException;
use stdClass;

/**
 * Класс для мониторинга ККТ
 *
 * @see https://online.atol.ru/files/API_service_information.pdf Документация
 */
class KktMonitor extends AtolClient
{
    /**
     * @inheritDoc
     */
    protected function getAuthEndpoint(): string
    {
        return $this->isTestMode()
            ? 'https://testonline.atol.ru/api/auth/v1/gettoken'
            : 'https://online.atol.ru/api/auth/v1/gettoken';
    }

    /**
     * @inheritDoc
     */
    protected function getMainEndpoint(): string
    {
        return $this->isTestMode()
            ? 'https://testonline.atol.ru/api/kkt/v1'
            : 'https://online.atol.ru/api/kkt/v1';
    }

    /**
     * @inheritDoc
     */
    public function auth(?string $login = null, ?string $password = null): bool
    {
        if (empty($this->getToken())) {
            $login && $this->setLogin($login);
            $password && $this->setPassword($password);
            if ($token = $this->doAuth()) {
                $this->setToken($token);
            }
        }
        return !empty($this->getToken());
    }

    /**
     * Получает от API информацию обо всех ККТ и ФН в рамках группы
     *
     * @param int|null $limit
     * @param int|null $offset
     * @return KktResponse
     * @throws GuzzleException
     * @see https://online.atol.ru/files/API_service_information.pdf Документация, стр 9
     */
    protected function fetchAll(?int $limit = null, ?int $offset = null): KktResponse
    {
        $params = [];
        $limit && $params['limit'] = $limit;
        $offset && $params['offset'] = $offset;
        return $this->sendRequest('GET', self::getUrlToMethod('cash-registers'), $params);
    }

    /**
     * Возвращает информацию обо всех ККТ и ФН в рамках группы
     *
     * @param int|null $limit
     * @param int|null $offset
     * @return KktResponse
     * @throws GuzzleException
     * @see https://online.atol.ru/files/API_service_information.pdf Документация, стр 9
     */
    public function getAll(?int $limit = null, ?int $offset = null): KktResponse
    {
        return $this->fetchAll($limit, $offset);
    }

    /**
     * Получает от API информацию о конкретной ККТ по её серийному номеру
     *
     * @param string $serial_number
     * @return KktResponse
     * @throws GuzzleException
     * @see https://online.atol.ru/files/API_service_information.pdf Документация, стр 11
     */
    protected function fetchOne(string $serial_number): KktResponse
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
     * @todo кастовать к отдельному классу со своими геттерами
     * @param string $serial_number
     * @return stdClass
     * @throws GuzzleException
     * @see https://online.atol.ru/files/API_service_information.pdf Документация, стр 11
     */
    public function getOne(string $serial_number): stdClass
    {
        return $this->fetchOne($serial_number)->getContent()->data;
    }
}
