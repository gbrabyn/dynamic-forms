<?php

use PHPUnit\Framework\TestCase;

/**
 *
 * @author GBrabyn
 */
class PositiveIntegerTest extends TestCase 
{
    public function testIsValid()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\PositiveInteger();

        $obj->setValue(0);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue('2');
        $this->assertTrue($obj->isValid());
        
        $obj->setValue(1.3);
        $this->assertFalse($obj->isValid());
        
        $obj->setValue(-4);
        $this->assertFalse($obj->isValid());
        
        $obj->setValue(null);
        $this->assertFalse($obj->isValid());
        
        $obj->setValue('');
        $this->assertFalse($obj->isValid());
        
        $obj->setValue('fasf');
        $this->assertFalse($obj->isValid());
    }
    
    public function testGetError()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\PositiveInteger();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj->getError());

        $error = new \GBrabyn\DynamicForms\Error('Not int');
        $obj2 = new GBrabyn\DynamicForms\FieldValidator\Integer($error);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj2->getError());
        
        $this->assertTrue( $obj2->getError() === $error );
    }
}
