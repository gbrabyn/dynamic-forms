<?php

use GBrabyn\DynamicForms\GroupValidator\WhenThen\ThenAllMustNotHave;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 * @author GBrabyn
 */
class ThenAllMustNotHaveTest extends PHPUnit_Framework_TestCase 
{
    public function testMeetConditions()
    {
        $field1 = new Field();
        $error1 = new Error('error 1');
        $field2 = new Field();
        $error2 = new Error('error 2');

        $obj1 = new ThenAllMustNotHave();
        $obj1->add($field1, ['1a', '1b'], $error1);
        $obj1->add($field2, ['2a', '2b'], $error2);
        
        $this->assertTrue($obj1->meetConditions());

        $obj2 = new ThenAllMustNotHave();
        $obj2->add($field1, ['1a', '1b'], $error1);
        $obj2->add($field2, ['2a', '2b'], $error2);
        
        $field1->setValue('3b', false);
        $field2->setValue('4b', false);
        $this->assertTrue($obj2->meetConditions());
        
        $obj3 = new ThenAllMustNotHave();
        $obj3->add($field1, ['1a', '1b'], $error1);
        $obj3->add($field2, ['2a', '2b'], $error2);
        
        $field2->setValue('2a', false);
        $this->assertFalse($obj3->meetConditions());
    }
    
    
    public function testErrors()
    {
        $field1 = new Field();
        $error1 = new Error('error 1');
        $field2 = new Field();
        $error2 = new Error('error 2');

        $obj1 = new ThenAllMustNotHave();
        $obj1->add($field1, ['1a', '1b'], $error1);
        $obj1->add($field2, ['2a', '2b'], $error2);
        $obj1->meetConditions();
        
        $this->assertEmpty($field1->getErrors());
        $this->assertEmpty($field2->getErrors());

        $field1->setValue('3a', false);
        $field1->setValue('4a', false);
        $obj1->meetConditions();
        
        $this->assertEmpty($field1->getErrors());
        $this->assertEmpty($field2->getErrors());

        $field2->setValue('2a', false);
        $obj1->meetConditions();
        
        $this->assertEmpty($field1->getErrors());
        $this->assertEquals($error2, $field2->getErrors()[0]);
        
        $field1->setValue('1b', false);
        $obj1->meetConditions();
        
        $this->assertEquals($error1, $field1->getErrors()[0]);
        $this->assertEquals($error2, $field2->getErrors()[0]);
    }
}
