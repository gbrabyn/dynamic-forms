<?php

use PHPUnit\Framework\TestCase;

/**
 *
 * @author GBrabyn
 */
class ScalarTest extends TestCase  
{
    public function testIsThereAnySyntaxError()
    {
        $var = new GBrabyn\DynamicForms\FieldValidator\Scalar();
        $this->assertTrue(is_object($var));
    }
    
    public function testIsValid()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\Scalar();

        $obj->setValue(0);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue('2');
        $this->assertTrue($obj->isValid());
        
        $obj->setValue('{foo:bar}');
        $this->assertTrue($obj->isValid());
        
        $obj->setValue(['aaa']);
        $this->assertFalse($obj->isValid());
        
        $obj->setValue(['aaa'=>['bbb']]);
        $this->assertFalse($obj->isValid());
    }
    
    public function testGetError()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\Scalar();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj->getError());

        $error = new \GBrabyn\DynamicForms\Error('Scalar', 'inputScalar', ['color'=>'blue']);
        $obj2 = new GBrabyn\DynamicForms\FieldValidator\Scalar($error);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj2->getError());
        
        $this->assertTrue( $obj2->getError() === $error );
    }
}
