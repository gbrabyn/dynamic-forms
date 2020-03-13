<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 9/14/18
 * Time: 12:39 PM
 */

class CallbackFieldValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testIsValid()
    {
        $callback = function($value){
            return $value === 2;
        };

        $mockError = $this
            ->getMockBuilder('\GBrabyn\DynamicForms\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $obj = new GBrabyn\DynamicForms\FieldValidator\Callback($callback, $mockError);

        $obj->setValue(2);
        $this->assertTrue($obj->isValid());

        $obj->setValue('2');
        $this->assertFalse($obj->isValid());
    }


    public function testGetError()
    {
        $callback = function($value){
            return (bool)$value;
        };

        $mockError = $this
            ->getMockBuilder('\GBrabyn\DynamicForms\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $obj = new GBrabyn\DynamicForms\FieldValidator\Callback($callback, $mockError);

        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj->getError());
    }


    public function testErrorPassedToCallback()
    {
        $callback = function($value, $error){
            $error->setArgs(['${num}'=>6]);

            return (bool)$value;
        };

        $error = new GBrabyn\DynamicForms\Error('No we need ${num}.', null, ['${num}'=>5]);
        $obj = new GBrabyn\DynamicForms\FieldValidator\Callback($callback, $error);
        $obj->isValid();

        $this->assertEquals(['${num}'=>6], $obj->getError()->getArgs());
    }


    public function testOptions()
    {
        $callback = function($value){
            return true;
        };

        $mockError = $this
            ->getMockBuilder('\GBrabyn\DynamicForms\Error')
            ->disableOriginalConstructor()
            ->getMock();

        $obj1 = new GBrabyn\DynamicForms\FieldValidator\Callback($callback, $mockError, ['useWhenEmpty'=>true]);
        $this->assertTrue($obj1->useWhenEmpty());

        $obj2 = new GBrabyn\DynamicForms\FieldValidator\Callback($callback, $mockError, ['useWhenEmpty'=>1]);
        $this->assertFalse($obj2->useWhenEmpty());

        $obj3 = new GBrabyn\DynamicForms\FieldValidator\Callback($callback, $mockError, ['falseKey'=>true]);
        $this->assertFalse($obj3->useWhenEmpty());

        $obj4 = new GBrabyn\DynamicForms\FieldValidator\Callback($callback, $mockError, ['useWhenEmpty'=>false]);
        $this->assertFalse($obj4->useWhenEmpty());
    }
}