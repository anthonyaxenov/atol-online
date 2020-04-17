<?php

namespace Unit;

use AtolOnline\Api\CorrectionSchema;
use AtolOnline\Api\SellSchema;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    /**
     * Тестирует корректность работы объекта схемы документа
     * прихода, возврата прихода, расхода, возврата расхода
     */
    public function testSellSchema()
    {
        $this->assertIsObject(SellSchema::get());
        $this->assertJson(SellSchema::json());
    }
    
    /**
     * Тестирует корректность работы объекта схемы документа
     * коррекции прихода, коррекции расхода
     */
    public function testCorrectionSchema()
    {
        $this->assertIsObject(CorrectionSchema::get());
        $this->assertJson(CorrectionSchema::json());
    }
}
