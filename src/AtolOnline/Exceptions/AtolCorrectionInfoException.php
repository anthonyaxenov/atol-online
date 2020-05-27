<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

namespace AtolOnline\Exceptions;

/**
 * Исключение, возникающее при попытке зарегистрировать документ без данных коррекции
 *
 * @package AtolOnline\Exceptions
 */
class AtolCorrectionInfoException extends AtolException
{
    /**
     * @var string Сообщение об ошибке
     */
    protected $message = 'Document must have correction info';
}