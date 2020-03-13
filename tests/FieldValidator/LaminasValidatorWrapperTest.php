<?php



/**
 * Description of LaminasValidatorWrapperTest
 *
 * @author GBrabyn
 */
class LaminasValidatorWrapperTest extends PHPUnit_Framework_TestCase
{
    
    public function testIsValid()
    {
        $laminasValidator = new \Laminas\Validator\StringLength(['min'=>2, 'max'=>3]);
        $obj = new GBrabyn\DynamicForms\FieldValidator\LaminasValidatorWrapper($laminasValidator);

        $obj->setValue(0);
        $this->assertFalse($obj->isValid());
        
        $obj->setValue(null);
        $this->assertFalse($obj->isValid());
        
        $obj->setValue('');
        $this->assertFalse($obj->isValid());
        
        $obj->setValue('the ');
        $this->assertFalse($obj->isValid());
        
        $obj->setValue('22');
        $this->assertTrue($obj->isValid());

        $obj->setValue('t e');
        $this->assertTrue($obj->isValid());
    }
    
    
    public function testGetError()
    {
        $laminasValidator = new \Laminas\Validator\StringLength(['min'=>2, 'max'=>3]);
        $obj = new GBrabyn\DynamicForms\FieldValidator\LaminasValidatorWrapper($laminasValidator);
        $obj->setValue('0');
        $obj->isValid();
        
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj->getError());
        
        $this->assertEquals('The input is less than 2 characters long', $obj->getError()->getMessage());

        $error = new \GBrabyn\DynamicForms\Error('Compulsory', 'inputCompulsory', ['color'=>'blue']);
        $obj2 = new GBrabyn\DynamicForms\FieldValidator\LaminasValidatorWrapper($laminasValidator, $error);
        $obj2->setValue(0);
        $obj2->isValid();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Error', $obj2->getError());
        
        $this->assertTrue( $obj2->getError() === $error );
    }
}
