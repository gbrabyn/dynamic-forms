<?php

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\GroupValidator\MutuallyRequired;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 * @author GBrabyn
 */
class MutuallyRequiredTest extends TestCase 
{
    
    public function testIsThereAnySyntaxError()
    {
        $obj = new MutuallyRequired([]);
        $this->assertTrue(is_object($obj));
    }
    
    
    public function testIsValid()
    {
        $obj = new MutuallyRequired([]);
        $this->assertTrue($obj->isValid());
        
        $field1 = new Field();
        $field1->setValue(null, false);
        
        $field2 = new Field();
        $field2->setValue('', false);
        
        $field3 = new Field();
        $field3->setValue(null, false);
        
        $fieldsGroup1 = [$field1, $field2, $field3];
        $obj1 = new MutuallyRequired($fieldsGroup1);
        $this->assertTrue($obj1->isValid());
        
        $field4 = new Field();
        $field4->setValue('e', false);
        
        $field5 = new Field();
        $field5->setValue('f', false);
        
        $field6 = new Field();
        $field6->setValue('g..', false);
        
        $fieldsGroup2 = [$field4, $field5, $field6];
        $obj2 = new MutuallyRequired($fieldsGroup2);
        $this->assertTrue($obj2->isValid());
        
        $fieldsGroup3 = [$field1, $field2, $field4];
        $obj3 = new MutuallyRequired($fieldsGroup3);
        $this->assertFalse($obj3->isValid());
        
        $this->assertFalse($field1->isValid());
        $this->assertFalse($field2->isValid());
        $this->assertTrue($field3->isValid());

        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $field1->getErrors()[0]);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $field2->getErrors()[0]);
        $this->assertEquals([], $field3->getErrors());
    }
    
    
    public function testGetError()
    {
        $obj1 = new MutuallyRequired([]);
        $obj1->isValid();
        $this->assertNull($obj1->getError());
        
        $field1 = new Field();
        $field1->setValue(null, false);
        
        $field2 = new Field();
        $field2->setValue('', false);
        
        $field3 = new Field();
        $field3->setValue('', false);
        
        $field4 = new Field();
        $field4->setValue('abc ', false);
        
        $obj2 = new MutuallyRequired([$field1, $field2, $field3]);
        $obj2->isValid();
        $this->assertNull($obj2->getError());
        
        $obj3 = new MutuallyRequired([$field1, $field2, $field4]);
        $obj3->isValid();
        $this->assertNull($obj3->getError());
    }
    
    
    public function testCorrectErrorGivenToFields()
    {
        $field1 = new Field();
        $field1->setValue('', false);
        
        $field2 = new Field();
        $field2->setValue('abc ', false);
        
        $error = new Error('blue', 'paint', ['{type}'=>'balls']);
        
        $obj = new MutuallyRequired([$field1, $field2], $error);
        $obj->isValid();
        
        $this->assertEquals($error, $field1->getErrors()[0]);
    }
    
    
//    public function testIsValid()
//    {
//        $obj = new MutuallyRequired([]);
//        $this->assertTrue($obj->isValid());
//        
//        $stub1 = $this  ->getMockBuilder('\GBrabyn\DynamicForms\Field')
//                        ->getMock();
//        $stub1  ->method('getValue')
//                ->willReturn(null);
//        
//        $stub2 = $this  ->getMockBuilder('\GBrabyn\DynamicForms\Field')
//                        ->getMock();
//        $stub2  ->method('getValue')
//                ->willReturn('');
//        
//        $stub3 = $this  ->getMockBuilder('\GBrabyn\DynamicForms\Field')
//                        ->getMock();
//        $stub3  ->method('getValue')
//                ->willReturn(null);
//        
//        $fieldsGroup1 = [$stub1, $stub2, $stub3];
//        
//        $obj1 = new MutuallyRequired($fieldsGroup1);
//        $this->assertTrue($obj1->isValid());
//
//        $stub4 = $this  ->getMockBuilder('\GBrabyn\DynamicForms\Field')
//                        ->getMock();
//        $stub4  ->method('getValue')
//                ->willReturn('e');
//        
//        $stub5 = $this  ->getMockBuilder('\GBrabyn\DynamicForms\Field')
//                        ->getMock();
//        $stub5  ->method('getValue')
//                ->willReturn('f');
//        
//        $stub6 = $this  ->getMockBuilder('\GBrabyn\DynamicForms\Field')
//                        ->getMock();
//        $stub6  ->method('getValue')
//                ->willReturn('g');
//        
//        $fieldsGroup2 = [$stub4, $stub5, $stub6];
//        
//        $obj2 = new MutuallyRequired($fieldsGroup2);
//        $this->assertTrue($obj2->isValid());
//        
//        
//        $fieldsGroup3 = [$stub1, $stub2, $stub4];
//        
//        $obj3 = new MutuallyRequired($fieldsGroup3);
//        $this->assertFalse($obj3->isValid());
//    }
}
