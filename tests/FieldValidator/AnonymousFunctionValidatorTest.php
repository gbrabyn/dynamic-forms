<?php

/**
 *
 * @author GBrabyn
 */
class AnonymousFunctionValidatorTest extends PHPUnit_Framework_TestCase 
{
    public function testIsValid()
    {
        $anonymousFunction = function($value){
            return $value === 2;
        };
        
        $mockError = $this
                        ->getMockBuilder('\GBrabyn\DynamicForms\Error')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $obj = new GBrabyn\DynamicForms\FieldValidator\AnonymousFunctionValidator($anonymousFunction, $mockError);

        $obj->setValue(2);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue('2');
        $this->assertFalse($obj->isValid());
    }
    
    
    public function testGetError()
    {
        $anonymousFunction = function($value){
            return (bool)$value;
        };
        
        $mockError = $this
                        ->getMockBuilder('\GBrabyn\DynamicForms\Error')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $obj = new GBrabyn\DynamicForms\FieldValidator\AnonymousFunctionValidator($anonymousFunction, $mockError);
        
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj->getError());
    }
}
