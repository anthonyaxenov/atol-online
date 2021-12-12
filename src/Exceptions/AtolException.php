<?php
/*
 * Copyright (c) 2020-2021 Антон Аксенов (Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

declare(strict_types = 1);

namespace AtolOnline\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * Исключение, возникающее при работе с АТОЛ Онлайн
 */
class AtolException extends Exception
{
    /**
     * @var int[] Теги ФФД
     */
    protected array $ffd_tags = [];

    /**
     * Конструктор
     *
     * @param string $message Сообщение
     * @param int[] $ffd_tags Переопредление тегов ФФД
     */
    #[Pure]
    public function __construct(string $message = '', array $ffd_tags = [])
    {
        $tags = implode(', ', $ffd_tags ?: $this->ffd_tags);
        parent::__construct(
            ($message ?: $this->message) . ($tags ? ' [Теги ФФД: ' . $tags . ']' : '')
        );
    }
}
