<?php

use PHPUnit\Framework\TestCase;

/**
 *
 * @author GBrabyn
 */
class RequiredTest extends TestCase 
{
    public function testIsValid()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\Required();

        $obj->setValue(0);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue('2');
        $this->assertTrue($obj->isValid());
        
        $obj->setValue(null);
        $this->assertFalse($obj->isValid());
        
        $obj->setValue('');
        $this->assertFalse($obj->isValid());
    }
    
    
    public function testGetError()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\Required();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj->getError());

        $error = new \GBrabyn\DynamicForms\Error('Compulsory', 'inputCompulsory', ['color'=>'blue']);
        $obj2 = new GBrabyn\DynamicForms\FieldValidator\Required($error);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj2->getError());
        
        $this->assertTrue( $obj2->getError() === $error );
    }
}
