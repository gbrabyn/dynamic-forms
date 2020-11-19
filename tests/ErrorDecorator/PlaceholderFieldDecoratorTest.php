<?php
/**
 * User: GBrabyn
 */
use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\ErrorDecorator\PlaceholderFieldDecorator;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Element\html5\Input;

class PlaceholderFieldDecoratorTest extends TestCase
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

    public function testToString()
    {
        $field = new Field();
        $error = new Error('Invalid input');
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $element = new Input($escaper, 'number');
        $element->setAttributes(new GBrabyn\DynamicForms\Element\Attributes());
        $decorator = new PlaceholderFieldDecorator('<div class="elError">${error}<br>${element}</div>');

        $field->addError($error);
        $element->setField($field);
        $decorator->setField($field);
        $decorator->setElement($element);

        $this->assertEquals('<div class="elError">Invalid input<br><input type="number" name="" value="" ></div>', (string)$decorator);
    }


    public function testTranslations1()
    {
        $field = new Field();
        $error = new Error('Invalid input', 'inputInvalid', ['{min}'=>2, '{max}'=>'3']);
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $element = new Input($escaper, 'number');
        $element->setAttributes(new GBrabyn\DynamicForms\Element\Attributes());

        $translator = $this->getTranslator();
        $translator->method('translate')->willReturn('Less the {min} chars. Must enter between {min} and {max} chars!');

        $decorator = new PlaceholderFieldDecorator('<div class="elError">${error}<br>${element}</div>', $translator);

        $hasTranslator = $this->invokeMethod($decorator, 'hasTranslator', []);

        $this->assertTrue($hasTranslator);

        $field->addError($error);
        $element->setField($field);
        $decorator->setField($field);
        $decorator->setElement($element);

        $this->assertEquals('<div class="elError">Less the 2 chars. Must enter between 2 and 3 chars!<br><input type="number" name="" value="" ></div>', (string)$decorator);
    }
}