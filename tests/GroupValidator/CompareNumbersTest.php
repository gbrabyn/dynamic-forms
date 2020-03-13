<?php

use GBrabyn\DynamicForms\GroupValidator\CompareNumbers;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 * @author GBrabyn
 */
class CompareNumbersTest extends PHPUnit_Framework_TestCase 
{
    
    public function testIsThereAnySyntaxError()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');
        
        $obj = new CompareNumbers($subject, $compareWith, '==', $error);
        $this->assertTrue(is_object($obj));
    }
    
    
    public function testInvalidComparisonTypeThrowsError()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');
        
        $this->expectException(\InvalidArgumentException::class);
        $obj = new CompareNumbers($subject, $compareWith, '===', $error);
    }
    
    
    public function testValidatesWhenNonNumericValuesCompared()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');
        
        $subject->setValue('abc', false);
        $compareWith->setValue(89, false);
        
        $obj1 = new CompareNumbers($subject, $compareWith, '==', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject->setValue(12, false);
        $compareWith->setValue('xyz', false);
        
        $obj2 = new CompareNumbers($subject, $compareWith, '<=', $error);
        
        $this->assertTrue($obj2->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
    }
    
    
    public function testComparesGreaterThan()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');
        
        $subject->setValue(2.303, false);
        $compareWith->setValue(2.3, false);
        
        $obj1 = new CompareNumbers($subject, $compareWith, '>', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject->setValue(2.3, false);
        $compareWith->setValue(2.303, false);
        
        $obj2 = new CompareNumbers($subject, $compareWith, '>', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject2 = new Field();
        $compareWith2 = new Field();
        
        $subject2->setValue(-2.3, false);
        $compareWith2->setValue(-2.30, false);
        
        $obj3 = new CompareNumbers($subject2, $compareWith2, '>', $error);

        $this->assertFalse($obj3->isValid());
        $this->assertFalse($subject2->isValid());
        $this->assertTrue($compareWith2->isValid());

        $subject3 = new Field();
        $compareWith3 = new Field();
        
        $subject3->setValue(2.29999, false);
        $compareWith3->setValue(2.30, false);
        
        $obj4 = new CompareNumbers($subject3, $compareWith3, '>', $error);

        $this->assertFalse($obj4->isValid());
        $this->assertFalse($subject3->isValid());
        $this->assertTrue($compareWith3->isValid());
    }
    
    public function testComparesGreaterThanOrEqual()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');
        
        $subject->setValue(2.303, false);
        $compareWith->setValue(2.3, false);
        
        $obj1 = new CompareNumbers($subject, $compareWith, '>=', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject->setValue(2.3, false);
        $compareWith->setValue(2.303, false);
        
        $obj2 = new CompareNumbers($subject, $compareWith, '>=', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject2 = new Field();
        $compareWith2 = new Field();
        
        $subject2->setValue(-2.3, false);
        $compareWith2->setValue(-2.30, false);
        
        $obj3 = new CompareNumbers($subject2, $compareWith2, '>=', $error);

        $this->assertTrue($obj3->isValid());
        $this->assertTrue($subject2->isValid());
        $this->assertTrue($compareWith2->isValid());

        $subject3 = new Field();
        $compareWith3 = new Field();
        
        $subject3->setValue(2.29999, false);
        $compareWith3->setValue(2.30, false);
        
        $obj4 = new CompareNumbers($subject3, $compareWith3, '>=', $error);

        $this->assertFalse($obj4->isValid());
        $this->assertFalse($subject3->isValid());
        $this->assertTrue($compareWith3->isValid());
    }
    
    
    public function testLessThan()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');

        $subject->setValue(2.3, false);
        $compareWith->setValue(2.303, false);
        
        $obj1 = new CompareNumbers($subject, $compareWith, '<', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $subject->setValue(2.303, false);
        $compareWith->setValue(2.3, false);
        
        $obj2 = new CompareNumbers($subject, $compareWith, '<', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $subject2 = new Field();
        $compareWith2 = new Field();
        
        $subject2->setValue(-2.3, false);
        $compareWith2->setValue(-2.30, false);
        
        $obj3 = new CompareNumbers($subject2, $compareWith2, '<', $error);

        $this->assertFalse($obj3->isValid());
        $this->assertFalse($subject2->isValid());
        $this->assertTrue($compareWith2->isValid());

        $subject3 = new Field();
        $compareWith3 = new Field();
        
        $subject3->setValue(2.3, false);
        $compareWith3->setValue(2.2999, false);
        
        $obj4 = new CompareNumbers($subject3, $compareWith3, '<', $error);

        $this->assertFalse($obj4->isValid());
        $this->assertFalse($subject3->isValid());
        $this->assertTrue($compareWith3->isValid());
    }
    
    
    public function testLessThanOrEqual()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');

        $subject->setValue(2.3, false);
        $compareWith->setValue(2.303, false);
        
        $obj1 = new CompareNumbers($subject, $compareWith, '<=', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $subject->setValue(2.303, false);
        $compareWith->setValue(2.3, false);
        
        $obj2 = new CompareNumbers($subject, $compareWith, '<=', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());

        $subject2 = new Field();
        $compareWith2 = new Field();
        
        $subject2->setValue(-2.3, false);
        $compareWith2->setValue(-2.30, false);
        
        $obj3 = new CompareNumbers($subject2, $compareWith2, '<=', $error);

        $this->assertTrue($obj3->isValid());
        $this->assertTrue($subject2->isValid());
        $this->assertTrue($compareWith2->isValid());

        $subject3 = new Field();
        $compareWith3 = new Field();
        
        $subject3->setValue(2.3, false);
        $compareWith3->setValue(2.2999, false);
        
        $obj4 = new CompareNumbers($subject3, $compareWith3, '<=', $error);

        $this->assertFalse($obj4->isValid());
        $this->assertFalse($subject3->isValid());
        $this->assertTrue($compareWith3->isValid());
    }
    
    
    public function testEqual()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');

        $subject->setValue(2.3, false);
        $compareWith->setValue(2.300, false);
        
        $obj1 = new CompareNumbers($subject, $compareWith, '==', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject->setValue(2.303, false);
        $compareWith->setValue(2.3, false);
        
        $obj2 = new CompareNumbers($subject, $compareWith, '==', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject2 = new Field();
        $compareWith2 = new Field();
        
        $subject2->setValue(-2.3, false);
        $compareWith2->setValue(-2.303, false);
        
        $obj3 = new CompareNumbers($subject2, $compareWith2, '==', $error);

        $this->assertFalse($obj3->isValid());
        $this->assertFalse($subject2->isValid());
        $this->assertTrue($compareWith2->isValid());
    }
    
    
    public function testNotEqual()
    {
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');

        $subject->setValue(2.3, false);
        $compareWith->setValue(2.303, false);
        
        $obj1 = new CompareNumbers($subject, $compareWith, '<>', $error);
        
        $this->assertTrue($obj1->isValid());
        $this->assertTrue($subject->isValid());
        $this->assertTrue($compareWith->isValid());
        
        $subject->setValue(2.3, false);
        $compareWith->setValue(2.300, false);
        
        $obj2 = new CompareNumbers($subject, $compareWith, '<>', $error);
        
        $this->assertFalse($obj2->isValid());
        $this->assertFalse($subject->isValid());
        $this->assertTrue($compareWith->isValid());
    }
    
}
