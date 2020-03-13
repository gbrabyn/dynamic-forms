<?php

use GBrabyn\DynamicForms\GroupValidator\Unique;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 * @author GBrabyn
 */
class UniqueTest extends PHPUnit_Framework_TestCase 
{
    public function testIsThereAnySyntaxError()
    {
        $obj = new Unique([]);
        $this->assertTrue(is_object($obj));
    }
    
    public function testIsValid()
    {
        $obj = new Unique([]);
        $this->assertNull($obj->getError());
        $this->assertTrue($obj->isValid());
        $this->assertNull($obj->getError());
        
        $field1 = new Field();
        $field1->setValue(null, false);
        
        $field2 = new Field();
        $field2->setValue('', false);
        
        $field3 = new Field();
        $field3->setValue(null, false);
        
        $fieldsGroup1 = [$field1, $field2, $field3];
        $obj1 = new Unique($fieldsGroup1);

        $this->assertTrue($obj1->isValid());
        $this->assertNull($obj1->getError());

        $field4 = new Field();
        $field4->setValue('e', false);
        
        $field5 = new Field();
        $field5->setValue('f', false);
        
        $field6 = new Field();
        $field6->setValue('g..', false);
        
        $fieldsGroup2 = [$field4, $field5, $field6];
        $obj2 = new Unique($fieldsGroup2);
        $this->assertTrue($obj2->isValid());
        $this->assertNull($obj2->getError());
        
        $field7 = new Field();
        $field7->setValue('e', false);
        
        $fieldsGroup3 = array_merge($fieldsGroup1, $fieldsGroup2, [$field7]);
        $obj3 = new Unique($fieldsGroup3);
        $this->assertFalse($obj3->isValid());
        $this->assertNull($obj3->getError());
        $this->assertInstanceOf(Error::class, $field7->getErrors()[0], 'Error not of class Error');
    }
    
    
    public function testCorrectErrorGivenToFields()
    {
        $field1 = new Field();
        $field1->setValue('', false);
        
        $field2 = new Field();
        $field2->setValue('abc ', false);
        
        $error = new Error('blue', 'paint', ['{type}'=>'balls']);
        
        $obj1 = new Unique([$field1, $field2], $error);
        $obj1->isValid();
        $this->assertEmpty($field1->getErrors());
        $this->assertEmpty($field2->getErrors());
        
        $field3 = new Field();
        $field3->setValue('abc ', false);
        
        $obj2 = new Unique([$field1, $field2, $field3], $error);
        $obj2->isValid();
        
        $this->assertEmpty($field1->getErrors());
        $this->assertEquals($error, $field2->getErrors()[0]);
        $this->assertEquals($error, $field3->getErrors()[0]);
    }
    
    
}
