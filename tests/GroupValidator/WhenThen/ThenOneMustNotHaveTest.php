<?php

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\GroupValidator\WhenThen\ThenOneMustNotHave;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 * @author GBrabyn
 */
class ThenOneMustNotHaveTest extends TestCase  
{
    public function testMeetConditions()
    {
        $field1 = new Field();
        $error1 = new Error('error 1');
        $field2 = new Field();
        $error2 = new Error('error 2');

        $obj1 = new ThenOneMustNotHave();
        $obj1->add($field1, ['1a', '1b'], $error1);
        $obj1->add($field2, ['2a', '2b'], $error2);
        
        $this->assertTrue($obj1->meetConditions());

        $field1->setValue('3b', false);
        $field2->setValue('4a', false);
        $this->assertTrue($obj1->meetConditions());
        
        $field2->setValue('2a', false);
        $this->assertTrue($obj1->meetConditions());
        
        $field1->setValue('1b', false);
        $this->assertFalse($obj1->meetConditions());
    }
    
    public function testErrors()
    {
        $field1 = new Field();
        $error1 = new Error('error 1');
        $field2 = new Field();
        $error2 = new Error('error 2');

        $obj1 = new ThenOneMustNotHave();
        $obj1->add($field1, ['1a', '1b'], $error1);
        $obj1->add($field2, ['2a', '2b'], $error2);
        
        $obj1->meetConditions();
        
        $this->assertEmpty($field1->getErrors());
        $this->assertEmpty($field2->getErrors());

        $field1->setValue('3b', false);
        $field2->setValue('4a', false);
        $obj1->meetConditions();
        
        $this->assertEmpty($field1->getErrors());
        $this->assertEmpty($field2->getErrors());
        
        $field2->setValue('2a', false);
        $obj1->meetConditions();
        
        $this->assertEmpty($field1->getErrors());
        $this->assertEmpty($field2->getErrors());
        
        $field1->setValue('1b', false);
        $obj1->meetConditions();
        
        $this->assertEquals($error1, $field1->getErrors()[0]);
        $this->assertEquals($error2, $field2->getErrors()[0]);
    }
}
