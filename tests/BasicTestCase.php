<?php
/**
 * Copyright (c) Антон Аксенов (aka Anthony Axenov)
 *
 * This code is licensed under MIT.
 * Этот код распространяется по лицензии MIT.
 * https://github.com/anthonyaxenov/atol-online/blob/master/LICENSE
 */

use AtolOnline\Entities\AtolEntity;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicTestCase
 */
class BasicTestCase extends TestCase
{
    /**
     *
     */
    public function setUp(): void
    {
        //parent::setUp();
        defined('ATOL_KKT_GROUP') ?: define('ATOL_KKT_GROUP', 'v4-online-atol-ru_4179');
        defined('ATOL_KKT_LOGIN') ?: define('ATOL_KKT_LOGIN', 'v4-online-atol-ru');
        defined('ATOL_KKT_PASS') ?: define('ATOL_KKT_PASS', 'iGFFuihss');
        defined('ATOL_CALLBACK_URL') ?: define('ATOL_CALLBACK_URL', 'http://example.com/callback');
    }
    
    /**
     * @param AtolOnline\Entities\AtolEntity $entity
     * @return $this
     */
    public function checkAtolEntity(AtolEntity $entity)
    {
        $this->assertJson((string)$entity);
        return $this;
    }
    
    /**
     *
     */
    public function tearDown(): void
    {
        
        //parent::tearDown();
    }
}