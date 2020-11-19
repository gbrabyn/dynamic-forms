<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 */
use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\ErrorDecorator\html5\Above;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Element\html5\Input;

class AboveTest extends TestCase
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
        $var = new Above();
        $this->assertTrue(is_object($var));
    }


    public function testToString()
    {
        $field = new Field();
        $error = new Error('Invalid input');
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $element = new Input($escaper, 'number');
        $element->setAttributes(new GBrabyn\DynamicForms\Element\Attributes());
        $above = new Above();

        $field->addError($error);
        $element->setField($field);
        $above->setField($field);
        $above->setElement($element);

        $this->assertEquals('<span class="error msgAbove">Invalid input</span><br><input type="number" name="" value="" class="error">', (string)$above);
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

        $above = new Above($translator);

        $hasTranslator = $this->invokeMethod($above, 'hasTranslator', []);

        $this->assertTrue($hasTranslator);

        $field->addError($error);
        $element->setField($field);
        $above->setField($field);
        $above->setElement($element);

        $this->assertEquals('<span class="error msgAbove">Less the 2 chars. Must enter between 2 and 3 chars!</span><br><input type="number" name="" value="" class="error">', (string)$above);
    }
}