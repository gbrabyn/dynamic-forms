<?php
use GBrabyn\DynamicForms;
use GBrabyn\DynamicForms\Manager\LaminasValidatorTraits;
use GBrabyn\DynamicForms\FieldValidator\LaminasValidatorWrapper;

/**
 *
 * @author GBrabyn
 */
class LaminasValidatorTraitsTest extends PHPUnit_Framework_TestCase
{
    protected function getMockTraits()
    {
        return $this->getMockForTrait(LaminasValidatorTraits::class);
    }

    public function testStringLength()
    {
        $translator = $this ->getMockBuilder('GBrabyn\DynamicForms\TranslatorInterface')
                            ->getMock();
        $obj = $this->getMockTraits();
        $obj->setLaminasTranslator(new DynamicForms\Manager\LaminasTranslatorWrapper($translator));

        $this->assertInstanceOf(LaminasValidatorWrapper::class, $this->getMockTraits()->stringLength(2, 4));
    }
    
    public function testDate()
    {
        $this->assertInstanceOf(LaminasValidatorWrapper::class, $this->getMockTraits()->date());
    }

    public function testTime()
    {
        $this->assertInstanceOf(LaminasValidatorWrapper::class, $this->getMockTraits()->time());
    }
    
    public function testBetween()
    {
        $this->assertInstanceOf(LaminasValidatorWrapper::class, $this->getMockTraits()->between(5, 9));
    }
    
    public function testLessThan()
    {
        $this->assertInstanceOf(LaminasValidatorWrapper::class, $this->getMockTraits()->lessThan(5, false));
    }
    
    public function testGreaterThan()
    {
        $this->assertInstanceOf(LaminasValidatorWrapper::class, $this->getMockTraits()->greaterThan(5, false));
    }
    
    public function testEmail()
    {
        $this->assertInstanceOf(LaminasValidatorWrapper::class, $this->getMockTraits()->email());
    }
    
    public function testUri()
    {
        $this->assertInstanceOf(LaminasValidatorWrapper::class, $this->getMockTraits()->uri());
    }
    
    public function testRegex()
    {
        $this->assertInstanceOf(LaminasValidatorWrapper::class, $this->getMockTraits()->regex('[a-z]'));
    }
    
    public function testCsrf()
    {
        $field = new \GBrabyn\DynamicForms\Field();
        $csrf = new Laminas\Validator\Csrf();
        $this->assertInstanceOf(DynamicForms\GroupValidator\AnonymousFunctionValidator::class, $this->getMockTraits()->csrf($field, $csrf));
        
        $validator = $this->getMockTraits()->csrf($field, $csrf);
        
        $this->assertFalse($validator->isValid());
        $this->assertInstanceOf(DynamicForms\Error::class, $validator->getError());
        
        $field->setValue('zxc', false);
        $this->assertFalse($validator->isValid());
        
        $field->setValue($csrf->getHash(), false);
        $this->assertTrue($validator->isValid());
    }
    
    public function testGetCsrfTokenValidator()
    {
        $this->assertInstanceOf(\Laminas\Validator\Csrf::class, $this->getMockTraits()->getCsrfTokenValidator());
    }
    
}
