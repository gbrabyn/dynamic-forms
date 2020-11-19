<?php
/**
 * Created by PhpStorm.
 */
use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\ErrorDecorator\CallbackDecorator;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Element\html5\Input;

class CallbackDecoratorTest extends TestCase
{
    public function getTranslator()
    {
        return $this
            ->getMockBuilder('GBrabyn\DynamicForms\TranslatorInterface')
            ->getMock();
    }


    public function testToString()
    {
        $field = new Field();
        $error = new Error('Invalid input');
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $element = new Input($escaper, 'number');
        $element->setAttributes(new GBrabyn\DynamicForms\Element\Attributes());
        $callbackDecorator = new CallbackDecorator(function($element, $field, $errorMessages){
            /* @var $element \GBrabyn\DynamicForms\Element\ElementAbstract */

            $attributes = $element->getAttributes();
            $attributes->add(['class'=>'error']);

            return 'aaa'.$element->getWithoutErrorMessage().'bbb'.$errorMessages[0].'ccc';
        });

        $field->setValue('zyx', false);
        $field->addError($error);
        $element->setField($field);
        $element->setName('bob');
        $callbackDecorator->setField($field);
        $callbackDecorator->setElement($element);

        $expected = 'aaa<input type="number" name="bob" value="zyx" class="error">bbbInvalid inputccc';

        $this->assertEquals($expected, (string)$callbackDecorator);
    }


    public function testTranslations()
    {
        $field = new Field();
        $error = new Error('Invalid input', 'invalidInput');
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $element = new Input($escaper, 'number');
        $element->setAttributes(new GBrabyn\DynamicForms\Element\Attributes());

        $translator = $this->getTranslator();
        $translator->method('translate')->willReturn('Translated Message!');

        $callbackDecorator = new CallbackDecorator(function($element, $field, $errorMessages, $translator){
            /* @var $element \GBrabyn\DynamicForms\Element\ElementAbstract */
            /* @var $translator \GBrabyn\DynamicForms\TranslatorInterface */
            return $translator->translate('Start Message').'aaa'.$element->getWithoutErrorMessage().'bbb'.$errorMessages[0].'ccc';
        }, $translator);

        $field->setValue('zyx', false);
        $field->addError($error);
        $element->setField($field);
        $element->setName('bob');
        $callbackDecorator->setField($field);
        $callbackDecorator->setElement($element);

        $expected = 'Translated Message!aaa<input type="number" name="bob" value="zyx" >bbbTranslated Message!ccc';

        $this->assertEquals($expected, (string)$callbackDecorator);
    }
}