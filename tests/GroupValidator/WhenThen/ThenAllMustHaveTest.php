<?php

use GBrabyn\DynamicForms\GroupValidator\WhenThen\ThenAllMustHave;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 * @author GBrabyn
 */
class ThenAllMustHaveTest extends PHPUnit_Framework_TestCase  
{

    public function testMeetConditions()
    {
        $field1 = new Field();
        $error1 = new Error('error 1');
        $field2 = new Field();
        $error2 = new Error('error 2');

        $obj1 = new ThenAllMustHave();
        $obj1->add($field1, ['1a', '1b'], $error1);
        $obj1->add($field2, ['2a', '2b'], $error2);
        
        $this->assertFalse($obj1->meetConditions());

        $obj2 = new ThenAllMustHave();
        $obj2->add($field1, ['1a', '1b'], $error1);
        $obj2->add($field2, ['2a', '2b'], $error2);
        
        $field1->setValue('1b', false);
        $this->assertFalse($obj2->meetConditions());
        
        $obj3 = new ThenAllMustHave();
        $obj3->add($field1, ['1a', '1b'], $error1);
        $obj3->add($field2, ['2a', '2b'], $error2);
        
        $field2->setValue('2a', false);
        $this->assertTrue($obj3->meetConditions());
    }
    
    public function testErrors()
    {
        $field1 = new Field();
        $error1 = new Error('error 1');
        $field2 = new Field();
        $error2 = new Error('error 2');

        $obj1 = new ThenAllMustHave();
        $obj1->add($field1, ['1a', '1b'], $error1);
        $obj1->add($field2, ['2a', '2b'], $error2);
        
        $field1->setValue('1b', false);
        $field2->setValue('2a', false);
        $obj1->meetConditions();
        
        $this->assertEmpty($field1->getErrors());
        $this->assertEmpty($field2->getErrors());

        $field1->setValue('3a', false);
        $obj1->meetConditions();
        
        $this->assertEquals($error1, $field1->getErrors()[0]);
        $this->assertEmpty($field2->getErrors());
        
        $field2->setValue('4b', false);
        $obj1->meetConditions();
        
        $this->assertEquals($error1, $field1->getErrors()[0]);
        $this->assertEquals($error2, $field2->getErrors()[0]);
    }
    
    
}
