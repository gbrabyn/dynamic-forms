<?php

use GBrabyn\DynamicForms\ErrorDecorator\html5\None;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Element\html5\Input;
use GBrabyn\DynamicForms\Exception\DynamicFormsException;

/**
 * Description of NoneTest
 *
 * @author GBrabyn
 */
class NoneTest extends PHPUnit_Framework_TestCase
{
    
    public function testToString()
    {
        $field = new Field();
        $error = new Error('Invalid input');
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $element = new Input($escaper, 'number');
        $element->setAttributes(new GBrabyn\DynamicForms\Element\Attributes());
        $none = new None();

        $field->addError($error);
        $element->setField($field);
        $none->setField($field);
        $none->setElement($element);
        
        $this->assertEquals('<input type="number" name="" value="" class="error">', (string)$none);
    }
}
