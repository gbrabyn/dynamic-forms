<?php
use PHPUnit\Framework\TestCase;

/**
 *
 * @author GBrabyn
 */
class FieldTest extends TestCase 
{
    
    public function testIsThereAnySyntaxError()
    {
        $var = new GBrabyn\DynamicForms\Field();
        $this->assertTrue(is_object($var));
    }
    
    
    public function testSetValue()
    {
        $var1 = new GBrabyn\DynamicForms\Field();
        $var1->setValue(99, false);
        
        $this->assertEquals(99, $var1->getValue());
        
        $var2 = new GBrabyn\DynamicForms\Field();
        $var2->setValue(99, true);
        
        $this->assertEquals(99, $var2->getValue());
    }
    
    
    public function testValidate()
    {
        $field = new GBrabyn\DynamicForms\Field();
        $requiredValidator = new GBrabyn\DynamicForms\FieldValidator\Required();

        $this->assertTrue($field->validate());
        $this->assertTrue($field->isValid());

        $field->addValidators($requiredValidator);
        $this->assertFalse($field->validate());
        $this->assertFalse($field->isValid());
        
        $field2 = new GBrabyn\DynamicForms\Field();
        
        $this->assertTrue($field2->isValid());
        
        $field2->setValue('xyz', false);
        $requiredValidator2 = clone $requiredValidator;
        $field2->addValidators([$requiredValidator2]);
        $this->assertTrue($field2->validate());
        $this->assertTrue($field2->isValid());
    }
    
    
    public function testAddError()
    {
        $field = new GBrabyn\DynamicForms\Field();
        $error = new \GBrabyn\DynamicForms\Error('xyz');
        $field->addError($error);
        
        $this->assertFalse($field->isValid());
        $this->assertEquals(1, count($field->getErrors()));
    }
    
    
    public function testTransformers()
    {
        $field = new GBrabyn\DynamicForms\Field();
        $transformer = new \GBrabyn\DynamicForms\Transform\Trim();
        
        $field->addTransformers($transformer);
        $field->setValue('   abc  ', true);
        
        $this->assertEquals('abc', $field->getValue());
    }
    
    
    public function testIsSelected()
    {
        $field = new GBrabyn\DynamicForms\Field();
        
        $this->assertFalse( $field->isSelected('a') );
        
        $field->setValue('a', true);
        $this->assertTrue( $field->isSelected('a') );
        
        $field->setValue('0', true);
        $this->assertTrue( $field->isSelected(0) );
        
        $field->setValue(0, true);
        $this->assertTrue( $field->isSelected(0) );
        
        $field->setValue(false, true);
        $this->assertTrue( $field->isSelected(0) );

        $field->setValue(true, true);
        $this->assertTrue( $field->isSelected(1) );

        $field->setValue(false, true);
        $this->assertTrue( $field->isSelected('false') );
        
        $field->setValue(true, true);
        $this->assertTrue( $field->isSelected('true') );
        
        $field->setValue('a', true);
        $this->assertFalse( $field->isSelected('b') );
        
        $field->setValue([1,2,3], true);
        $this->assertFalse( $field->isSelected('b') );
        $this->assertTrue( $field->isSelected('2') );
        
    }


    public function testEnsureArray()
    {
        $field1 = new GBrabyn\DynamicForms\Field();
        $field1->ensureArray();
        $this->assertEquals([], $field1->getValue());

        $field2 = new GBrabyn\DynamicForms\Field();
        $field2->ensureArray();
        $field2->setValue('bbbbbb', true);
        $this->assertEquals([], $field2->getValue());

        $field3 = new GBrabyn\DynamicForms\Field();
        $field3->ensureArray();
        $field3->setValue(['b'=>'c'], true);
        $this->assertEquals(['b'=>'c'], $field3->getValue());

        $field3 = new GBrabyn\DynamicForms\Field();
        $field3->ensureArray();
        $field3->setValue(['a', 'b', 'c'], true);
        $this->assertEquals(['a', 'b', 'c'], $field3->getValue());
    }

}
