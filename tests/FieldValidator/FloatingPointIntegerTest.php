<?php

/**
 *
 * @author GBrabyn
 */

use PHPUnit\Framework\TestCase;

class FloatingPointIntegerTest extends TestCase 
{
    public function testIsValid()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\FloatingPointInteger();

        $obj->setValue(0);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue('2');
        $this->assertTrue($obj->isValid());
        
        $obj->setValue('1.3');
        $this->assertTrue($obj->isValid());
        
        $obj->setValue(-4.01);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue(null);
        $this->assertFalse($obj->isValid());
        
        $obj->setValue('');
        $this->assertFalse($obj->isValid());
        
        $obj->setValue('fasf');
        $this->assertFalse($obj->isValid());
    }

    public function testGetError()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\FloatingPointInteger();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj->getError());

        $error = new \GBrabyn\DynamicForms\Error('Not float');
        $obj2 = new GBrabyn\DynamicForms\FieldValidator\FloatingPointInteger($error);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj2->getError());

        $this->assertTrue( $obj2->getError() === $error );
    }
}
