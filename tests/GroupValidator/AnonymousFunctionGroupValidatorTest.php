<?php

/**
 *
 * @author GBrabyn
 */
class AnonymousFunctionGroupValidatorTest extends PHPUnit_Framework_TestCase 
{
    public function testIsValid()
    {
        $anonymousFunction1 = function(){
            return false;
        };
        
        $anonymousFunction2 = function(){
            return true;
        };
        
        $mockError = $this
                        ->getMockBuilder('\GBrabyn\DynamicForms\Error')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $obj1 = new GBrabyn\DynamicForms\GroupValidator\AnonymousFunctionValidator($anonymousFunction1, $mockError);
        $obj2 = new GBrabyn\DynamicForms\GroupValidator\AnonymousFunctionValidator($anonymousFunction2, $mockError);
        
        $this->assertFalse($obj1->isValid());
        $this->assertTrue($obj2->isValid());
    }
    
    
    public function testGetError()
    {
        $anonymousFunction = function(){
            return true;
        };
        
        $mockError = $this
                        ->getMockBuilder('\GBrabyn\DynamicForms\Error')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $obj = new GBrabyn\DynamicForms\GroupValidator\AnonymousFunctionValidator($anonymousFunction, $mockError);
        
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj->getError());
    }
}
