<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/30/18
 * Time: 11:57 AM
 */

class EnsureArrayTest extends PHPUnit_Framework_TestCase
{
    public function testGetValue()
    {
        $obj = new GBrabyn\DynamicForms\Transform\EnsureArray();

        $obj->setValue(['aaa', 'bbb']);
        $this->assertEquals(['aaa', 'bbb'], $obj->getValue());

        $obj->setValue(null);
        $this->assertEquals([], $obj->getValue());

        $obj->setValue('aaaa');
        $this->assertEquals([], $obj->getValue());
    }
}