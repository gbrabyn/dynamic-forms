<?php
/**
 * User: GBrabyn
 */

use PHPUnit\Framework\TestCase;

class SequentialArrayTest extends TestCase
{
    public function testIsValid()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\SequentialArray();

        $obj->setValue([]);
        $this->assertTrue($obj->isValid());

        $obj->setValue(['a', 'b', 'c', 'd']);
        $this->assertTrue($obj->isValid());

        $obj->setValue([0=>'a', 1=>'b', 3=>'c', 4=>'d']);
        $this->assertFalse($obj->isValid());

        $obj->setValue([1=>'a', 2=>'b', 3=>'c', 4=>'d']);
        $this->assertFalse($obj->isValid());

        $obj->setValue('aaaaaaaaaaa');
        $this->assertFalse($obj->isValid());

        $obj->setValue(0);
        $this->assertFalse($obj->isValid());

        $obj->setValue(['aaa'=>'bbb']);
        $this->assertFalse($obj->isValid());
    }

    public function testGetError()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\SequentialArray();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj->getError());

        $error = new \GBrabyn\DynamicForms\Error('Array', 'inputArray', ['color'=>'blue']);
        $obj2 = new GBrabyn\DynamicForms\FieldValidator\SequentialArray($error);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj2->getError());

        $this->assertTrue( $obj2->getError() === $error );
    }
}