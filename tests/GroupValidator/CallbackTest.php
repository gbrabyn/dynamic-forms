<?php
/**
 * User: GBrabyn
 */

use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
{
    public function testIsValid()
    {
        $callback1 = function(){
            return false;
        };

        $callback2 = function(){
            return true;
        };

        $mockError = $this
            ->getMockBuilder('\GBrabyn\DynamicForms\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $obj1 = new GBrabyn\DynamicForms\GroupValidator\Callback($callback1, $mockError);
        $obj2 = new GBrabyn\DynamicForms\GroupValidator\Callback($callback2, $mockError);

        $this->assertFalse($obj1->isValid());
        $this->assertTrue($obj2->isValid());
    }

    public function testArgsPassedToCallback()
    {
        $callback = function($callbackArgs, $errorObj){
            $errorObj->setArgs($callbackArgs);

            return false;
        };

        $error = new GBrabyn\DynamicForms\Error('No we need ${num}.');
        $args = ['${num}'=>5];

        $obj = new GBrabyn\DynamicForms\GroupValidator\Callback($callback, $error, $args);
        $obj->isValid();

        $this->assertSame($args, $error->getArgs());
    }

    public function testGetError()
    {
        $callback = function(){
            return true;
        };

        $mockError = $this
            ->getMockBuilder('\GBrabyn\DynamicForms\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $obj = new GBrabyn\DynamicForms\GroupValidator\Callback($callback, $mockError);

        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj->getError());
    }
}