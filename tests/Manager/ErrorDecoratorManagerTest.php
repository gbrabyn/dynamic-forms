<?php

use GBrabyn\DynamicForms\Manager\ErrorDecoratorManager;

/**
 *
 * @author GBrabyn
 */
class ErrorDecoratorManagerTest extends PHPUnit_Framework_TestCase  
{
    
    public function getTranslator()
    {
        return $this
                ->getMockBuilder('GBrabyn\DynamicForms\TranslatorInterface')
                ->getMock();
    }
    
    public function testWrongDocTypeThrowsInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $obj = new ErrorDecoratorManager('xyz');
    }
    
    
    public function testAbove()
    {
        $obj = new ErrorDecoratorManager('HTML5');
        $above = $obj->above();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\html5\Above::class, $above);
    }

    public function testBelow()
    {
        $obj = new ErrorDecoratorManager('HTML5', $this->getTranslator());
        $below = $obj->below();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\html5\Below::class, $below);
    }

    public function testCallback()
    {
        $obj = new ErrorDecoratorManager('HTML5', $this->getTranslator());
        $callBackErrorDecorator = $obj->callback(function($element, $field, $errorMessages, $translator){
            return null;
        });

        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\CallbackDecorator::class, $callBackErrorDecorator);
    }

    public function testForm()
    {
        $obj = new ErrorDecoratorManager('HTML5');
        $form = $obj->form();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\html5\Form::class, $form);
    }

    public function testNone()
    {
        $obj = new ErrorDecoratorManager('HTML5');
        $none = $obj->none();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\html5\None::class, $none);
    }
    
    public function testRightSide()
    {
        $obj = new ErrorDecoratorManager('HTML5');
        $right = $obj->rightSide();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\html5\RightSide::class, $right);
    }
    
    public function testSeparate()
    {
        $obj = new ErrorDecoratorManager('HTML5');
        $separate = $obj->separate();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\html5\Separate::class, $separate);
    }

    public function testSeparateRaw()
    {
        $obj = new ErrorDecoratorManager('HTML5');
        $separateRaw = $obj->separateRaw();

        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\SeparateRaw::class, $separateRaw);
    }

    public function testStandAloneCallback()
    {
        $obj = new ErrorDecoratorManager('HTML5');
        $standAloneCallBack = $obj->standAloneCallback(function($errorMessages, $translator){
            return;
        });

        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\StandAloneCallbackDecorator::class, $standAloneCallBack);
    }

    public function testPlaceholder()
    {
        $obj = new ErrorDecoratorManager('HTML5');
        $placeholder = $obj->placeholder('<div class="elError">${error}<br>${element}</div>');

        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\PlaceholderFieldDecorator::class, $placeholder);
    }
    
    public function testPlaceholderStandAlone()
    {
        $obj = new ErrorDecoratorManager('HTML5');
        $placeholder = $obj->placeholderStandAlone('<div class="elError">${error}</div>');

        $this->assertInstanceOf(\GBrabyn\DynamicForms\ErrorDecorator\PlaceholderStandAloneDecorator::class, $placeholder);
    }
}
