<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 9/17/18
 * Time: 10:49 AM
 */

use GBrabyn\DynamicForms\ErrorDecorator\SeparateRaw;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Element\html5\Input;
use GBrabyn\DynamicForms\Exception\DynamicFormsException;

class SeparateRawTest extends PHPUnit_Framework_TestCase
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