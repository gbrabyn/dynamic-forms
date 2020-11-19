<?php

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\ErrorDecorator\html5\RightSide;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Element\html5\Input;
use GBrabyn\DynamicForms\Exception\DynamicFormsException;

/**
 *
 * @author GBrabyn
 */
class RightSideTest extends TestCase 
{
    public function getTranslator()
    {
        return $this
                ->getMockBuilder('GBrabyn\DynamicForms\TranslatorInterface')
                ->getMock();
    }

    
    public function invokeMethod($object, $methodName, array $parameters=[])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        
        return $method->invokeArgs($object, $parameters);
    }

    
    public function testIsThereAnySyntaxError()
    {
        $var = new RightSide();
        $this->assertTrue(is_object($var));
    }
    

    public function testToString()
    {
        $field = new Field();
        $error = new Error('Invalid input');
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $element = new Input($escaper, 'number');
        $element->setAttributes(new GBrabyn\DynamicForms\Element\Attributes());
        $rightSide = new RightSide();

        $field->addError($error);
        $element->setField($field);
        $rightSide->setField($field);
        $rightSide->setElement($element);
        
        $this->assertEquals('<input type="number" name="" value="" class="error"><span class="error msgRight">Invalid input</span>', (string)$rightSide);
    }
    
    
    public function testTranslations1()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $field = new Field();
        $error = new Error('Invalid input', 'inputInvalid', ['{min}'=>2, '{max}'=>'3']);
        $element = new Input($escaper, 'number');
        $element->setAttributes(new GBrabyn\DynamicForms\Element\Attributes());
        
        $translator = $this->getTranslator();
        $translator->method('translate')->willReturn('Less the {min} chars. Must enter between {min} and {max} chars!');

        $rightSide = new RightSide($translator);
        
        $hasTranslator = $this->invokeMethod($rightSide, 'hasTranslator', []);
        
        $this->assertTrue($hasTranslator);

        $field->addError($error);
        $element->setField($field);
        $rightSide->setField($field);
        $rightSide->setElement($element);
        
        $this->assertEquals('<input type="number" name="" value="" class="error"><span class="error msgRight">Less the 2 chars. Must enter between 2 and 3 chars!</span>', (string)$rightSide);
    }
    
    
    public function testTranslations2()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $field = new Field();
        $error = new Error('Invalid input!', 'inputInvalid');
        $element = new Input($escaper, 'number');
        $element->setAttributes(new GBrabyn\DynamicForms\Element\Attributes());
        
        $translator = $this->getTranslator();
        $translator->method('translate')->willReturn('inputInvalid');

        $rightSide = new RightSide($translator);

        $field->addError($error);
        $element->setField($field);
        $rightSide->setField($field);
        $rightSide->setElement($element);
        
        $this->assertEquals('<input type="number" name="" value="" class="error"><span class="error msgRight">Invalid input!</span>', (string)$rightSide);
    }
    
    
    
    
    // TODO - test for the exceptions
}
