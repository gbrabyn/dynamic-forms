<?php

use GBrabyn\DynamicForms\Manager\TransformManager;

/**
 *
 * @author GBrabyn
 */
class TransformManagerTest extends PHPUnit_Framework_TestCase  
{

    public function testEmptyStringToNull()
    {
        $obj = new TransformManager();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Transform\EmptyStringToNull::class, $obj->emptyStringToNull());   
    }

    public function testRemoveNumberLocalisation()
    {
        $obj = new TransformManager();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Transform\RemoveNumberLocalisation::class, $obj->removeNumberLocalisation('de_DE'));   
    }

    public function testTrim()
    {
        $obj = new TransformManager();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Transform\Trim::class, $obj->trim());   
    }

    public function testEnsureArray()
    {
        $obj = new TransformManager();

        $this->assertInstanceOf(\GBrabyn\DynamicForms\Transform\EnsureArray::class, $obj->ensureArray());
    }
    
    
}
