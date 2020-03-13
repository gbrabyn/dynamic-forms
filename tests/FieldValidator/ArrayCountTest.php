<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/30/18
 * Time: 12:53 PM
 */

class ArrayCountTest extends PHPUnit_Framework_TestCase
{
    public function testIsValid()
    {
        $obj1 = new GBrabyn\DynamicForms\FieldValidator\ArrayCount();
        $obj1->setValue(['a', 'b', 'c', 'd']);
        $this->assertTrue($obj1->isValid());

        $obj2 = new GBrabyn\DynamicForms\FieldValidator\ArrayCount(2);

        $obj2->setValue(['a', 'b', 'c', 'd']);
        $this->assertTrue($obj2->isValid());

        $obj2->setValue(['a', 'b']);
        $this->assertTrue($obj2->isValid());

        $obj2->setValue(['a']);
        $this->assertFalse($obj2->isValid());
        $this->assertEquals('numItemsLessThan', $obj2->getError()->getTranslationKey());

        $obj2->setValue('aaa');
        $this->assertFalse($obj2->isValid());
        $this->assertEquals('inputCorrupted', $obj2->getError()->getTranslationKey());

        $obj3 = new GBrabyn\DynamicForms\FieldValidator\ArrayCount(0, 2);
        $obj3->setValue(['a', 'b', 'c', 'd']);
        $this->assertFalse($obj3->isValid());
        $this->assertEquals('numItemsGreaterThan', $obj3->getError()->getTranslationKey());

        $obj3->setValue(['a', 'b']);
        $this->assertTrue($obj3->isValid());

        $obj4 = new GBrabyn\DynamicForms\FieldValidator\ArrayCount(2, 2);
        $obj4->setValue(['a', 'b', 'c', 'd']);
        $this->assertFalse($obj4->isValid());
        $this->assertEquals('numItemsNotBetween', $obj4->getError()->getTranslationKey());

        $obj4->setValue(['a', 'b']);
        $this->assertTrue($obj4->isValid());
    }



}