<?php

use GBrabyn\DynamicForms\GroupValidator\CompareStrings;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 * @author GBrabyn
 */
class CompareStringsTest extends PHPUnit_Framework_TestCase 
{
    public function testIsThereAnySyntaxError()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');
        
        $obj = new CompareStrings($subject, $compareWith, '==', $error);
        $this->assertTrue(is_object($obj));
    }
    
    
    public function testComparesGreaterThan()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');
        
        $subject->setValue('abc', false);
        $compareWith->setValue('ab', false);
        
        $obj1 = new CompareStrings($subject, $compareWith, '>', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject->setValue('ab', false);
        $compareWith->setValue('ab0', false);
        
        $obj2 = new CompareStrings($subject, $compareWith, '>', $error);

        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject2 = new Field();
        $compareWith2 = new Field();
        
        $subject2->setValue('  !', false);
        $compareWith2->setValue('  ! ', false);
        
        $obj3 = new CompareStrings($subject2, $compareWith2, '>', $error);

        $this->assertFalse($obj3->isValid());
        $this->assertFalse($subject2->isValid());
        $this->assertTrue($compareWith2->isValid());

        $subject3 = new Field();
        $compareWith3 = new Field();
        
        $subject3->setValue('Zzzzzzz', false);
        $compareWith3->setValue('zzzz', false);
        
        $obj4 = new CompareStrings($subject3, $compareWith3, '>', $error);

        $this->assertFalse($obj4->isValid());
        $this->assertFalse($subject3->isValid());
        $this->assertTrue($compareWith3->isValid());
    }
    
    
    public function testComparesGreaterThanOrEqual()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');
        
        $subject->setValue('ab ', false);
        $compareWith->setValue('ab', false);
        
        $obj1 = new CompareStrings($subject, $compareWith, '>=', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject->setValue('ab', false);
        $compareWith->setValue('ab ', false);
        
        $obj2 = new CompareStrings($subject, $compareWith, '>=', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject2 = new Field();
        $compareWith2 = new Field();
        
        $subject2->setValue('ab', false);
        $compareWith2->setValue('ab', false);
        
        $obj3 = new CompareStrings($subject2, $compareWith2, '>=', $error);

        $this->assertTrue($obj3->isValid());
        $this->assertTrue($subject2->isValid());
        $this->assertTrue($compareWith2->isValid());

        $subject3 = new Field();
        $compareWith3 = new Field();
        
        $subject3->setValue('ab', false);
        $compareWith3->setValue('cd', false);
        
        $obj4 = new CompareStrings($subject3, $compareWith3, '>=', $error);

        $this->assertFalse($obj4->isValid());
        $this->assertFalse($subject3->isValid());
        $this->assertTrue($compareWith3->isValid());
    }
    
    
    public function testLessThan()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');

        $subject->setValue('aaa', false);
        $compareWith->setValue('bbb', false);
        
        $obj1 = new CompareStrings($subject, $compareWith, '<', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $subject->setValue('bbb', false);
        $compareWith->setValue('aaa', false);
        
        $obj2 = new CompareStrings($subject, $compareWith, '<', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $subject2 = new Field();
        $compareWith2 = new Field();
        
        $subject2->setValue('aaa', false);
        $compareWith2->setValue('aaa', false);
        
        $obj3 = new CompareStrings($subject2, $compareWith2, '<', $error);

        $this->assertFalse($obj3->isValid());
        $this->assertFalse($subject2->isValid());
        $this->assertTrue($compareWith2->isValid());
    }
    
    
    public function testLessThanOrEqual()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');

        $subject->setValue('aaa', false);
        $compareWith->setValue('bbb', false);
        
        $obj1 = new CompareStrings($subject, $compareWith, '<=', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $subject->setValue('bbb', false);
        $compareWith->setValue('aaa', false);
        
        $obj2 = new CompareStrings($subject, $compareWith, '<=', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $subject2 = new Field();
        $compareWith2 = new Field();
        
        $subject2->setValue('aaa', false);
        $compareWith2->setValue('aaa', false);
        
        $obj3 = new CompareStrings($subject2, $compareWith2, '<=', $error);

        $this->assertTrue($obj3->isValid());
        $this->assertTrue($subject2->isValid());
        $this->assertTrue($compareWith2->isValid());
    }
    

    public function testEqual()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');

        $subject->setValue(' !0  a', false);
        $compareWith->setValue(' !0  a', false);
        
        $obj1 = new CompareStrings($subject, $compareWith, '==', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject->setValue('aaaa', false);
        $compareWith->setValue('bbb', false);
        
        $obj2 = new CompareStrings($subject, $compareWith, '==', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject2 = new Field();
        $compareWith2 = new Field();
        
        $subject2->setValue('bbb', false);
        $compareWith2->setValue('aaaa', false);
        
        $obj3 = new CompareStrings($subject2, $compareWith2, '==', $error);

        $this->assertFalse($obj3->isValid());
        $this->assertFalse($subject2->isValid());
        $this->assertTrue($compareWith2->isValid());
    }
    
    
    public function testNotEqual()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');

        $subject->setValue('baa', false);
        $compareWith->setValue('baA', false);
        
        $obj1 = new CompareStrings($subject, $compareWith, '<>', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject->setValue('baa', false);
        $compareWith->setValue('baa', false);
        
        $obj2 = new CompareStrings($subject, $compareWith, '<>', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());
    }


    public function testWithEmptyVals()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');

        $subject->setValue('baa', false);
        $compareWith->setValue('', false);

        $obj1 = new CompareStrings($subject, $compareWith, '<', $error);

        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $compareWith->setValue(null, false);
        $obj2 = new CompareStrings($subject, $compareWith, '<', $error);

        $this->assertTrue($obj2->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $subject->setValue('', false);
        $compareWith->setValue('baa', false);
        $obj3 = new CompareStrings($subject, $compareWith, '<', $error);

        $this->assertTrue($obj3->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $subject->setValue(null, false);
        $compareWith->setValue('baa', false);
        $obj4 = new CompareStrings($subject, $compareWith, '<', $error);

        $this->assertTrue($obj4->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
    }
    
}
