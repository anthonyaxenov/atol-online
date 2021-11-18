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

use Throwable;

/**
 * Исключение, возникающее при ошибке валидации email
 */
class InvalidEmailException extends AtolException
{
    /**
     * @inheritDoc
     */
    protected array $ffd_tags = [
        1008,
        1117,
    ];

    /**
     * AtolEmailValidateException constructor.
     *
     * @param string $email
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $email = '', $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: "Invalid email: '$email'", $code, $previous);
    }
}