<?php
/**
 * User: GBrabyn
 */
use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\ErrorDecorator\SeparateRaw;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Element\html5\Input;
use GBrabyn\DynamicForms\Exception\DynamicFormsException;

class SeparateRawTest extends TestCase
{
    public function getTranslator()
    {
        return $this
            ->getMockBuilder('GBrabyn\DynamicForms\TranslatorInterface')
            ->getMock();
    }


    public function testToString()
    {
        $error = new Error('Invalid input');
        $separateRaw = new SeparateRaw($this->getTranslator());
        $separateRaw->setErrors([$error]);

        $this->assertEquals('Invalid input', (string)$separateRaw);
    }
}