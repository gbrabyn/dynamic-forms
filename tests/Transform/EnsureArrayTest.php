<?php
/**
 * User: GBrabyn
 */

use PHPUnit\Framework\TestCase;

class EnsureArrayTest extends TestCase
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