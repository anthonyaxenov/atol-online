<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Exceptions;

use Exception;
use Throwable;

/**
 * Исключение, возникающее при работе с АТОЛ Онлайн
 *
 * @package AtolOnline\Exceptions
 */
class AtolException extends Exception
{
    /**
     * @var int[] Теги ФФД
     */
    protected $ffd_tags = null;
    
    /**
     * AtolException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
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
     * @return array|null
     */
    protected function getFfdTags(): ?array
    {
        return $this->ffd_tags;
    }
}