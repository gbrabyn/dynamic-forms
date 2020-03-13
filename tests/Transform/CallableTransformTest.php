<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 9/14/18
 * Time: 2:20 PM
 */

class CallableTransformTest extends PHPUnit_Framework_TestCase
{
    public function testGetValue()
    {
        $callable = function($value){
            return $value.'_zzz';
        };

        $obj1 = new GBrabyn\DynamicForms\Transform\CallableTransform($callable);

        $obj1->setValue('blue');
        $this->assertEquals('blue_zzz', $obj1->getValue());

        $obj2 = new GBrabyn\DynamicForms\Transform\CallableTransform('strtoupper');
        $obj2->setValue('blue');
        $this->assertNotEquals('blue', $obj2->getValue());
        $this->assertEquals('BLUE', $obj2->getValue());
    }
}