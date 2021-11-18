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
use Throwable;

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
     * AtolException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $this->message;
        if ($this->getFfdTags()) {
            $message .= ' [FFD tags: '.implode(', ', $this->getFfdTags()).']';
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * Возвращает теги ФФД, с которыми связано исключение
     *
     * @return array
     */
    protected function getFfdTags(): array
    {
        return $this->ffd_tags;
    }
}