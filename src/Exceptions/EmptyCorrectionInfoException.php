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

use AtolOnline\Entities\CorrectionInfo;

/**
 * Исключение, возникающее при попытке зарегистрировать документ коррекции без соотв. данных
 */
class EmptyCorrectionInfoException extends AtolException
{
    protected $message = 'Документ должен содержать объект ' . CorrectionInfo::class;
}
